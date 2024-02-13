<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Entity\Vacation;
use App\Enum\NotificationTypeEnum;
use App\Repository\UserRepository;
use App\Repository\VacationRepository;
use App\Service\Emailing;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RemoveVacationAction extends AbstractController
{
    public function __construct(
        private readonly VacationRepository     $vacationRepository,
        private readonly NotificationService    $notificationService,
        private readonly Emailing               $emailing,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }


    public function __invoke(Request $request, Vacation $vacation): JsonResponse
    {
            /** @var User $user */
            $user = $this->getUser();
            $userLeaveRequest = $vacation->getUser();
            $slug = NotificationTypeEnum::LEAVE_CANCELLED;

            $this->notificationService->sendNotification(
                emailing:  $this->emailing,
                sender:  $user,
                slug: $slug,
                affiliates: [$userLeaveRequest],
                action: $userLeaveRequest,
            );
            $this->entityManager->remove($vacation);
            $json = [
                'status' => 'success',
                'code' => '200',
                'message' => 'Leave is deleted'
            ];

        return new JsonResponse($json);
    }
}
