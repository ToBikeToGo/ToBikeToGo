<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Constants\Globals;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class GetSlotsAction extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Get the available slots for a given shop.
     *
     * @param Shop $shop The shop entity
     * @return array|JsonResponse The available slots array or a JSON response with a 422 status code if the date is invalid
     */
    public function __invoke(Shop $shop, Request $request)
    {
        // Retrieve data from the body of the request
        $data = json_decode($request->getContent(), true);
        $date = $data['date'];

        // Verify that the date is valid
        if (!\DateTime::createFromFormat('Y-m-d', $date)) {
            return new JsonResponse(status: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $date = new \DateTime($date);

        // Get the day of the week
        $dow = $date->format('N');

        // Format the date
        $date = $date->format('Y-m-d');

        // Get the employees with the role "employee"
        $employees = $shop->getFranchise()->getUsers()->filter(function ($employee) {
            return in_array(Globals::ROLE_EMPLOYEE, $employee->getRoles());
        });

        // Initialize arrays for start and end times, vacation start and end times, and not available employees
        $startTimeArray = [];
        $endTimeArray = [];
        $startTimeArrayVacation = [];
        $endTimeArrayVacation = [];
        $notAvailableEmployees = [];

        // Process the employees and populate the arrays
        $this->processEmployees($employees, $dow, $date, $startTimeArray, $endTimeArray, $startTimeArrayVacation, $endTimeArrayVacation, $notAvailableEmployees);

        // Filter the vacation start and end times based on the available employees
        $startTimeArrayVacation = array_intersect_key($startTimeArrayVacation, $startTimeArray);
        $endTimeArrayVacation = array_intersect_key($endTimeArrayVacation, $endTimeArray);

        // Merge the start and end times with the vacation start and end times
        $startTimeArray = array_replace($startTimeArray, $startTimeArrayVacation);
        $endTimeArray = array_replace($endTimeArray, $endTimeArrayVacation);

        // Remove not available employees from the start and end times arrays
        $startTimeArray = array_diff_key($startTimeArray, array_flip($notAvailableEmployees));
        $endTimeArray = array_diff_key($endTimeArray, array_flip($notAvailableEmployees));

        // If there are no available start or end times, return an empty array
        if (empty($startTimeArray) || empty($endTimeArray)) {
            return json_decode(json_encode(['slots' => []]), false);
        }

        // Round the start times and end times to the nearest 30 minutes
        $startTimeArray = $this->roundStartTimes($startTimeArray);
        $endTimeArray = $this->roundEndTimes($endTimeArray);

        // Get the bikes for the shop
        $bikes = $shop->getBikes()->getValues();
        $bookings = [];

        // Process the bikes and populate the bookings array
        $this->processBikes($bikes, $date, $bookings);

        // Create the employees availability array
        $employeesAvailability = $this->createEmployeesAvailabilityArray($startTimeArray, $endTimeArray);

        // Find the earliest start time and latest end time
        $earliestStartTime = min($startTimeArray);
        $latestEndTime = max($endTimeArray);

        // Generate the slots array based on the employees availability, earliest start time, latest end time, and bookings
        $slots = $this->generateSlotsArray($employeesAvailability, $earliestStartTime, $latestEndTime, $bookings);


        return json_decode(json_encode($slots), false);
    }

    /**
     * Process the employees and populate the start and end times arrays, vacation start and end times arrays, and not available employees array.
     *
     * @param Collection $employees The employees collection
     * @param int $dow The day of the week
     * @param string $date The date
     * @param array $startTimeArray The start time array
     * @param array $endTimeArray The end time array
     * @param array $startTimeArrayVacation The vacation start time array
     * @param array $endTimeArrayVacation The vacation end time array
     * @param array $notAvailableEmployees The not available employees array
     * @return void
     */
    private function processEmployees(Collection $employees, $dow, $date, array &$startTimeArray, array &$endTimeArray, array &$startTimeArrayVacation, array &$endTimeArrayVacation, array &$notAvailableEmployees): void
    {
        // Loop through the employees
        foreach ($employees as $employee) {

            // Filter the schedules based on the day of the week and date validity 
            $schedules = $employee->getSchedules()->filter(function ($schedule) use ($dow, $date) {
                $startValidity = $schedule->getStartValidity();
                $endValidity = $schedule->getEndValidity();

                return $schedule->getDow() == $dow &&
                    (
                        ($endValidity === null && $startValidity->format('Y-m-d') <= $date) ||
                        ($endValidity !== null && $startValidity->format('Y-m-d') <= $date && $endValidity->format('Y-m-d') >= $date)
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
            $startTimeArray[$employee->getId()] = $scheduleStartTime->format('H:i:s');
            $endTimeArray[$employee->getId()] = $scheduleEndTime->format('H:i:s');

            // Get the vacations of the employee
            $vacations = $employee->getVacations()->getValues();

            // Loop through the vacations
            foreach ($vacations as $vacation) {

                $vacationStartDate = $vacation->getStartDate()->format('Y-m-d');
                $vacationEndDate = $vacation->getEndDate()->format('Y-m-d');
                $vacationStartTime = $vacation->getStartDate()->format('H:i:s');
                $vacationEndTime = $vacation->getEndDate()->format('H:i:s');

                // If the vacation is not valid for the date, skip it and populate the not available employees array
                if ($vacationStartDate < $date && $vacationEndDate > $date) {
                    $notAvailableEmployees[] = $employee->getId();
                    continue;
                }

                // If the vacation is valid for the date, populate the vacation start and end times arrays
                if ($vacationStartDate == $date && $scheduleStartTime <= $vacation->getEndDate()) {
                    $endTimeArrayVacation[$employee->getId()] = $vacationStartTime;
                }

                if ($vacationEndDate == $date && $scheduleEndTime >= $vacation->getStartDate()) {
                    $startTimeArrayVacation[$employee->getId()] = $vacationEndTime;
                }
            }
        }
    }

    /**
     * Round the start times to the nearest 30 minutes.
     *
     * @param array $startTimeArray The start time array
     * @return array The rounded start time array
     */
    private function roundStartTimes(array $startTimeArray): array
    {
        return array_map(function ($time) {
            $startTime = new \DateTime($time);
            $roundedMinutes = ($startTime->format('i') >= 30) ? 30 : 0;
            $startTime->setTime($startTime->format('H'), $roundedMinutes)->modify('+30 minutes');
            return $startTime->format('H:i');
        }, $startTimeArray);
    }

    /**
     * Round the end times to the nearest 30 minutes.
     *
     * @param array $endTimeArray The end time array
     * @return array The rounded end time array
     */
    private function roundEndTimes(array $endTimeArray): array
    {
        return array_map(function ($time) {
            $endTime = new \DateTime($time);
            $roundedMinutes = ($endTime->format('i') >= 30) ? 30 : 0;
            $endTime->setTime($endTime->format('H'), $roundedMinutes);
            return $endTime->format('H:i');
        }, $endTimeArray);
    }

    /**
     * Process the bikes and populate the bookings array.
     *
     * @param array $bikes The bikes array
     * @param string $date The date
     * @param array $bookings The bookings array
     * @return void
     */
    private function processBikes(array $bikes, string $date, array &$bookings): void
    {
        // Loop through the bikes
        foreach ($bikes as $bike) {
            // Filter the bookings based on the date
            $bookingsForBike = $bike->getBookings()->filter(function ($booking) use ($date) {
                return $booking->getStartDate()->format('Y-m-d') == $date;
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
            $bookings = array_merge($bookings, $bookingsForBike);
        }
    }

    /**
     * Create the employees availability array.
     *
     * @param array $startTimeArray The start time array
     * @param array $endTimeArray The end time array
     * @return array The employees availability array
     */
    private function createEmployeesAvailabilityArray(array $startTimeArray, array $endTimeArray): array
    {
        return array_map(function ($employeeId, $startTime) use ($endTimeArray) {
            return [
                "start" => $startTime,
                "end" => $endTimeArray[$employeeId],
            ];
        }, array_keys($startTimeArray), $startTimeArray);
    }

    /**
     * Generate the slots array based on the employees availability, earliest start time, latest end time, and bookings.
     *
     * @param array $employeesAvailability The employees availability array
     * @param string $earliestStartTime The earliest start time
     * @param string $latestEndTime The latest end time
     * @param array $bookings The bookings array
     * @return array The slots array
     */
    private function generateSlotsArray(array $employeesAvailability, string $earliestStartTime, string $latestEndTime, array $bookings): array
    {
        $slots = [];
        $startTime = new \DateTime($earliestStartTime);
        $endTime = new \DateTime($latestEndTime);

        // Loop while the start time is less than the end time
        while ($startTime < $endTime) {
            $currentSlot = $startTime->format('H:i');

            // Filter the employees availability based on the current slot
            $totalAvailableEmployees = count(array_filter($employeesAvailability, function ($availability) use ($currentSlot) {
                return $availability['start'] <= $currentSlot && $availability['end'] > $currentSlot;
            }));

            // Filter the bookings based on the current slot
            $totalAvailableEmployees -= count(array_filter($bookings, function ($booking) use ($currentSlot) {
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
