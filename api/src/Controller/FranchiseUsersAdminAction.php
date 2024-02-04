<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Entity\Franchise;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class FranchiseUsersAdminAction extends AbstractController {
    /**
     * @param Franchise $franchise
     * @return Collection<User>
     */
    public function __invoke(Franchise $franchise) {
        $users = $franchise->getUsers();
        $users = $users->filter(function($user) {
            return in_array('ROLE_PROVIDER', $user->getRoles());
        });

        return $users;
    }
}
