<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Entity\Schedule;
use App\Entity\Shop;
use App\Enum\NotificationTypeEnum;
use App\Enum\RolesEnum;
use App\Service\Emailing;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateMemberAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Emailing $emailing,
        private readonly NotificationService $notificationService
    ) {
    }

    public function __invoke(Request $request, User $user): JsonResponse
    {

        $userData = json_decode($request->getContent(), true);

        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setEmail($userData['email']);
        if (isset($userData['schedules'])) {
            foreach ($userData['schedules'] as $sc) {
                $user->getSchedules()->map(function ($schedule) use ($sc) {
                    if ($schedule->getDow() == $sc['dow']) {
                        $this->em->remove($schedule);
                    }
                });
                $schedule = new Schedule();
                $schedule->setDow($sc['dow']);
                $schedule->setStartTime(new \DateTime($sc['startTime']))
                    ->setEndTime(new \DateTime($sc['endTime']))
                    ->addUser($user);
                $schedule->setStartValidity(new \DateTime());
                $this->em->persist($schedule);
            }
        }
        $this->em->persist($user);
        $this->em->flush();

        $json = [
            'status' => 'success',
            'code' => '200',
            'message' => 'User account updated successfully'
        ];

        return $this->json($json);
    }
}
