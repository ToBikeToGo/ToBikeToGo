<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Entity\Booking;
use App\Entity\Vacation;
use App\Enum\NotificationTypeEnum;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use App\Repository\VacationRepository;
use App\Service\Emailing;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RemoveBookingAction extends AbstractController
{
    public function __construct(
        private readonly NotificationService    $notificationService,
        private readonly Emailing               $emailing,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }


    public function __invoke(Request $request, Booking $booking): JsonResponse
    {
            /** @var User $user */
            $user = $this->getUser();
            $userLeaveRequest = $booking->getUser();
            $slug = NotificationTypeEnum::RESERVATION_CANCELLED;

            $this->notificationService->sendNotification(
                emailing:  $this->emailing,
                sender:  $user,
                slug: $slug,
                affiliates: [$userLeaveRequest],
                action: $userLeaveRequest,
            );
            $this->entityManager->remove($booking);
            $json = [
                'status' => 'success',
                'code' => '200',
                'message' => 'Booking is deleted'
            ];

        return new JsonResponse($json);
    }
}
