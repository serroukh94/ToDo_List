<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['TASK_EDIT', 'TASK_DELETE', 'TASK_TOGGLE'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ROLE_ADMIN can do anything
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        if ($attribute === 'TASK_TOGGLE') {
            // user must be the author of the task or have admin role
            return $user === $subject->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles(), true);
        }

        if (in_array($attribute, ['TASK_EDIT', 'TASK_DELETE'])) {
            // user must be the author of the task, or have admin role in case of anonymous task
            return ($user === $subject->getAuthor()) || (in_array('ROLE_ADMIN', $user->getRoles(), true)
                    && null === $subject->getAuthor());
        }

        return false;
    }
}