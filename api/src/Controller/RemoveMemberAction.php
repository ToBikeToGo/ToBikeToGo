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

class RemoveMemberAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Emailing $emailing,
        private readonly NotificationService $notificationService
    ) {
    }

    public function __invoke( User $user): JsonResponse
    {
        $shop = $this->em->getRepository(Shop::class)->findOneBy(['id' => $user->getId()]);

        foreach ($user->getShops() as $shop) {
            $user->removeFranchise($shop->getFranchise());
            $user->removeShop($shop);
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
