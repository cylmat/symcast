<?php

namespace SecurityAuth\Security\Voter;

use SecurityAuth\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // use with isGranted()
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MY_MANAGE', 'POST_VIEW'])
            && $subject instanceof Article;
    }

    // called if supports return true
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Article $subject */

        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'MY_MANAGE':
                // logic to determine if the user can EDIT
                // return true or false

                // only if current is author of the article
                if ($subject->getAuthor() == $user) {
                    return true;
                }

                // otherwise check for admin
                if ($this->security->isGranted('ROLE_ADMIN_ARTICLE')) {
                    return true;
                }

                return false;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
