<?php

namespace App\Security\Voter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SecurityVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute == 'PATCH') {
            return true;
        }
        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }
        if ($subject->getId() !== $token->getUser()->getId()) {
            return false;
        }
        return true;
    }
}
