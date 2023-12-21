<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Constants\Globals;
use App\Entity\Auth\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class GetSlotsAction extends AbstractController
{

    private const TIME_FORMAT = 'H:i:s';

    private const DATE_FORMAT = 'Y-m-d';

    private array $startTimeArray = [];
    private array $endTimeArray = [];
    private array $startTimeArrayVacation = [];
    private array $endTimeArrayVacation = [];
    private array $notAvailableEmployees = [];
    private array $bookings = [];
    private \DateTime|string $date = '';
    private string $dow = '';

    /**
     * Get the available slots for a given shop.
     *
     * @param Shop $shop The shop entity
     * @return array|JsonResponse The available slots array or a JSON response
     */
    public function __invoke(Shop $shop, Request $request)
    {
        // Retrieve data from the body of the request
        $data = json_decode($request->getContent(), true);
        $this->date = $data['date'];

        // Verify that the date is valid
        if (!\DateTime::createFromFormat(self::DATE_FORMAT, $this->date)) {
            return new JsonResponse(status: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->date = new \DateTime($this->date);

        // Get the day of the week
        $this->dow = $this->date->format('N');

        // Format the date
        $this->date = $this->toDateFormat($this->date);

        // Get the employees with the role "employee"
        $employees = $shop->getFranchise()->getUsers()->filter(function ($employee) {
            return in_array(Globals::ROLE_EMPLOYEE, $employee->getRoles());
        });

        // Process the employees and populate the arrays
        $this->processEmployees($employees);

        // Process the employees and update the start and end times arrays
        $this->processEmployeesAvailability();

        // If there are no available start or end times, return an empty array
        if (empty($this->startTimeArray) || empty($this->endTimeArray)) {
            return json_decode(json_encode(['slots' => []]), false);
        }

        // Round the start times and end times to the nearest 30 minutes
        $this->startTimeArray = $this->roundStartTimes();
        $this->endTimeArray = $this->roundEndTimes();

        // Get the bikes for the shop
        $bikes = $shop->getBikes()->getValues();

        // Process the bikes and populate the bookings array
        $this->processBikes($bikes);

        // Create the employees availability array
        $employeesAvailability = $this->createEmployeesAvailabilityArray();

        // Find the earliest start time and latest end time
        $earliestStartTime = min($this->startTimeArray);
        $latestEndTime = max($this->endTimeArray);

        // Generate the slots array
        $slots = $this->generateSlotsArray($employeesAvailability, $earliestStartTime, $latestEndTime);


        return new JsonResponse($slots, Response::HTTP_OK);
    }


    /**
     * Process the employees and populate the start and end times arrays,
     * vacation start and end times arrays,
     * and not available employees array.
     *
     * @param Collection $employees The employees collection
     * @return void
     */
    private function processEmployees(Collection $employees): void
    {
        // Loop through the employees
        foreach ($employees as $employee) {
            // Filter the schedules based on the day of the week and date validity
            $schedules = $employee->getSchedules()->filter(function ($schedule) {
                $startValidity = $schedule->getStartValidity();
                $endValidity = $schedule->getEndValidity();

                return $schedule->getDow() == $this->dow &&
                    (
                        ($endValidity === null && $this->toDateFormat($startValidity) <= $this->date) ||
                        (
                            $endValidity !== null && $this->toDateFormat($startValidity) <= $this->date &&
                            $this->toDateFormat($endValidity) >= $this->date
                        )
                    );
            });

            if ($schedules->count() == 0) {
                continue;
            }

            // Get the first schedule
            $schedule = $schedules->first();
            $scheduleStartTime = $schedule->getStartTime();
            $scheduleEndTime = $schedule->getEndTime();

            // Populate the start and end times arrays
            $this->startTimeArray[$employee->getId()] = $this->toTimeFormat($scheduleStartTime);
            $this->endTimeArray[$employee->getId()] = $this->toTimeFormat($scheduleEndTime);

            // Get the vacations of the employee
            $vacations = $employee->getVacations()->getValues();

            // Loop through the vacations
            $this->processVacations($employee, $vacations, $scheduleStartTime, $scheduleEndTime);
        }
    }

    /**
     * Process the employees and update the start and end times arrays.
     *
     * @return void
     */
    private function processEmployeesAvailability(): void
    {
        // Filter the vacation start and end times based on the available employees
        $this->startTimeArrayVacation = array_intersect_key($this->startTimeArrayVacation, $this->startTimeArray);
        $this->endTimeArrayVacation = array_intersect_key($this->endTimeArrayVacation, $this->endTimeArray);

        // Merge the start and end times with the vacation start and end times
        $this->startTimeArray = array_replace($this->startTimeArray, $this->startTimeArrayVacation);
        $this->endTimeArray = array_replace($this->endTimeArray, $this->endTimeArrayVacation);

        // Remove not available employees from the start and end times arrays
        $this->startTimeArray = array_diff_key($this->startTimeArray, array_flip($this->notAvailableEmployees));
        $this->endTimeArray = array_diff_key($this->endTimeArray, array_flip($this->notAvailableEmployees));
    }

    /**
     * Process the vacations of an employee and populate the vacation start and end times arrays,
     * and not available employees array.
     *
     * @param $employee The employee
     * @param array $vacations The vacations array
     * @param \DateTime $scheduleStartTime The schedule start time
     * @param \DateTime $scheduleEndTime The schedule end time
     * @return void
     */
    private function processVacations
    (
        User $employee,
        array $vacations,
        \DateTime $scheduleStartTime,
        \DateTime $scheduleEndTime
    ): void {
        foreach ($vacations as $vacation) {
            $vacationStartDate = $this->toDateFormat($vacation->getStartDate());
            $vacationEndDate = $this->toDateFormat($vacation->getEndDate());
            $vacationStartTime = $this->toTimeFormat($vacation->getStartDate());
            $vacationEndTime = $this->toTimeFormat($vacation->getEndDate());

            // If the vacation is not valid for the date, skip it and populate the not available employees array
            if ($vacationStartDate < $this->date && $vacationEndDate > $this->date) {
                $this->notAvailableEmployees[] = $employee->getId();
                continue;
            }

            // If the vacation is valid for the date, populate the vacation start and end times arrays
            if ($vacationStartDate == $this->date && $scheduleStartTime <= $vacation->getEndDate()) {
                $this->endTimeArrayVacation[$employee->getId()] = $vacationStartTime;
            }

            if ($vacationEndDate == $this->date && $scheduleEndTime >= $vacation->getStartDate()) {
                $this->startTimeArrayVacation[$employee->getId()] = $vacationEndTime;
            }
        }
    }

    /**
     * Round the start times to the nearest 30 minutes.
     *
     * @return array The rounded start time array
     */
    private function roundStartTimes(): array
    {
        return array_map(function ($time) {
            $startTime = new \DateTime($time);
            $roundedMinutes = ($startTime->format('i') >= 30) ? 30 : 0;
            $startTime->setTime(intval($startTime->format('H')), $roundedMinutes)->modify('+30 minutes');
            return $startTime->format('H:i');
        }, $this->startTimeArray);
    }

    /**
     * Round the end times to the nearest 30 minutes.
     *
     * @return array The rounded end time array
     */
    private function roundEndTimes(): array
    {
        return array_map(function ($time) {
            $endTime = new \DateTime($time);
            $roundedMinutes = ($endTime->format('i') >= 30) ? 30 : 0;
            $endTime->setTime(intval($endTime->format('H')), $roundedMinutes);
            return $endTime->format('H:i');
        }, $this->endTimeArray);
    }

    /**
     * Process the bikes and populate the bookings array.
     *
     * @param array $bikes The bikes array
     * @return void
     */
    private function processBikes(array $bikes): void
    {
        // Loop through the bikes
        foreach ($bikes as $bike) {
            // Filter the bookings based on the date
            $bookingsForBike = $bike->getBookings()->filter(function ($booking) {
                return $this->toDateFormat($booking->getStartDate()) == $this->date;
            });

            if ($bookingsForBike->count() == 0) {
                continue;
            }

            // Populate the bookings array with the start times of the bookings
            $bookingsForBike = array_map(function ($booking) {
                $startDate = $booking->getStartDate();
                $startDate->setTime($startDate->format('H'), round($startDate->format('i') / 30) * 30);
                return $startDate->format('H:i');
            }, $bookingsForBike->getValues());

            // Merge the bookings array with the bookings for the bike
            $this->bookings = array_merge($this->bookings, $bookingsForBike);
        }
    }

    /**
     * Create the employees availability array.
     *
     * @return array The employees availability array
     */
    private function createEmployeesAvailabilityArray(): array
    {
        return array_map(function ($employeeId, $startTime) {
            return [
                "start" => $startTime,
                "end" => $this->endTimeArray[$employeeId],
            ];
        }, array_keys($this->startTimeArray), $this->startTimeArray);
    }

    /**
     * Format a date to the DATE_FORMAT constant.
     *
     * @param \DateTime $date The date to format
     * @return string The formatted date
     */
    private function toDateFormat(\DateTime $date)
    {
        return $date->format(self::DATE_FORMAT);
    }

    /**
     * Format a date to the TIME_FORMAT constant.
     *
     * @param \DateTime $date The date to format
     * @return string The formatted date
     */
    private function toTimeFormat(\DateTime $date)
    {
        return $date->format(self::TIME_FORMAT);
    }

    /**
     * Generate the slots array based on the employees availability, earliest start time, latest end time, and bookings.
     *
     * @param array $employeesAvailability The employees availability array
     * @param string $earliestStartTime The earliest start time
     * @param string $latestEndTime The latest end time
     * @return array The slots array
     */
    private function generateSlotsArray(
        array $employeesAvailability,
        string $earliestStartTime,
        string $latestEndTime,
    ): array {
        $slots = [];
        $startTime = new \DateTime($earliestStartTime);
        $endTime = new \DateTime($latestEndTime);

        // Loop while the start time is less than the end time
        while ($startTime < $endTime) {
            $currentSlot = $startTime->format('H:i');

            // Filter the employees availability based on the current slot
            $totalAvailableEmployees = count(
                array_filter($employeesAvailability, function ($availability) use ($currentSlot) {
                    return $availability['start'] <= $currentSlot && $availability['end'] > $currentSlot;
                })
            );

            // Filter the bookings based on the current slot
            $totalAvailableEmployees -= count(array_filter($this->bookings, function ($booking) use ($currentSlot) {
                return $booking == $currentSlot;
            }));

            // If the total available employees is less than 0, set it to 0
            $totalAvailableEmployees = max(0, $totalAvailableEmployees);

            // Populate the slots array
            $slots['slots'][$currentSlot] = $totalAvailableEmployees;

            // Proceed to the next slot
            $startTime->modify('+30 minutes');
        }

        return $slots;
    }
}
