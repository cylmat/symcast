<?php

namespace Forms\Validator;

use SecurityAuth\Repository\ArticleRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MyUniqUserValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(ArticleRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \Forms\Validator\MyUniqUser */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$articles = $this->userRepository->findBy(['title' => $value])) {
            return;
        }

        if (strlen($value) < 15) { // for testing
            return;
        }

        $titles = array_map(function($article){ return $article->getTitle(); }, $articles);

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->alpha_message . ':' . join(',', $titles))
            ->setParameter('**title**', $value)
            ->addViolation();
    }
}
