<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Entity\Schedule;
use App\Entity\Shop;
use App\Enum\NotificationTypeEnum;
use App\Service\Emailing;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterMemberAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Emailing $emailing,
        private readonly NotificationService $notificationService
    ) {
    }

    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);

        $shop = $this->em->getRepository(Shop::class)->findOneBy(['id' => $userData['shop']]);

        $user = new User();
        $user->setEmail($userData['email']);
        $plaintextPassword = $shop->getLabel();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setStatus(false);
        $user->setPassword($hashedPassword);
        $user->addShop($shop);
        $user->addFranchise($shop->getFranchise());

        foreach ($userData['schedules'] as $sc) {
            $schedule = new Schedule();
            $schedule->setDow($sc['dow']);
            $schedule->setStartTime(new \DateTime($sc['startTime']))
                ->setEndTime(new \DateTime($sc['endTime']))
                ->addUser($user);
            $this->em->persist($schedule);
        }
        $this->em->persist($user);
        $this->em->flush();
        $slug = NotificationTypeEnum::EMPLOYEE_ADDED;

        /** @var User $admin */
        $admin = $this->getUser();
        $this->notificationService->sendNotification(
            emailing: $this->emailing,
            sender: $admin,
            slug: $slug,
            affiliates: $userData['franchise']->getUsers()->getValues(),
            action: $user
        );

        $bytes = bin2hex(random_bytes(16));
        $user->setToken($bytes);
        $this->emailing->sendEmailingTemplate(
            [$user->getEmail()],
            1,
            $user->getToken(),
            $user->getId()
        );

        $json = [
            'status' => 'success',
            'code' => '200',
            'message' => 'User account is registered'
        ];

        return $this->json($json);
    }
}
