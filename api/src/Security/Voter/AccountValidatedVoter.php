<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountValidatedVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // Supporte uniquement l'attribut 'ROLE_USER_VALIDATED'
        return $attribute === 'ROLE_USER_VALIDATED';
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Récupérer l'utilisateur à partir du token
        $user = $token->getUser();

        // Vérifier si l'utilisateur est valide (validated à true)
        if ($user instanceof User && $user->isValidated()) {
            return true;
        }

        return false;
    }
}
