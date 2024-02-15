<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Entity\Franchise;
use App\Enum\NotificationTypeEnum;
use App\Repository\FranchiseRepository;
use App\Service\Emailing;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly NotificationService    $notificationService,
        private readonly FranchiseRepository    $franchiseRepository,
        private readonly Security               $security,
        private readonly Emailing               $emailing
    )
    {
    }

    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher) :JsonResponse
    {
        $newUser = json_decode($request->getContent(), true);
        $bytes = bin2hex(random_bytes(16));

        $user = new User();
        $user->setLastname($newUser['lastname']);
        $user->setFirstname($newUser['firstname']);
        $user->setEmail($newUser['email']);

        $plaintextPassword = $newUser['password'];
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user->setPassword($hashedPassword);
        $user->setPhone($newUser['phone']);
        $user->setLocale($newUser['locale']);
        $user->setStatus(false);
        $user->setToken($bytes);
        $this->em->persist($user);
        $this->em->flush();

        $this->emailing->sendEmailingTemplate([$user->getEmail()], 1, $user->getToken(), $user->getId());

        $json = [
            'status' => 'success',
            'code' => '200',
            'message' => 'User is created and email send.'
        ];
        return new JsonResponse($json);
    }
}
