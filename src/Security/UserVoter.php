<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    const IS_SUPER_ADMIN = 'IS_SUPER_ADMIN';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::IS_SUPER_ADMIN])) {
            return false;
        }
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }
        switch ($attribute) {
            case self::IS_SUPER_ADMIN:
                return $this->isSuperAdmin($user);
                break;
        }
        throw new \LogicException('This code should not be reached!');
    }

    public function isSuperAdmin(User $user)
    {
        return in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }
}
