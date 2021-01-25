<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class IsVerifiedVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, ['isVerifiedCheck'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var User $subject */
        if (!$subject->isVerified()) throw new AccessDeniedException('This functionality is disabled for non-verified users.');

        return true;
    }
}