<?php

namespace App\Security;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class ArticleVoter extends Voter
{
    const EDIT = 'EDIT';
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }
        if (!$subject instanceof Article) {
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
            case self::EDIT:
                return $this->canEdit($subject, $user, $token);
                break;
        }
        throw new \LogicException('This code should not be reached!');
    }

    public function canEdit(Article $article, User $user, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_SUPER_ADMIN']) | $this->decisionManager->decide($token, ['ROLE_BLOGGER'])) {
            return true;
        }

        return $article->getUser()->getId() == $user->getId();
    }
}
