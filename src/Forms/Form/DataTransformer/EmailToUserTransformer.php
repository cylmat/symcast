<?php

namespace Forms\Form\DataTransformer;

use SecurityAuth\Entity\User;
use SecurityAuth\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{
    private $userRepository;
    private $itsMyFinderCallback;

    public function __construct(UserRepository $userRepository, callable $itsMyFinderCallback)
    {
        $this->userRepository = $userRepository;
        $this->itsMyFinderCallback = $itsMyFinderCallback;
    }

    /**
     * @var User|null $value
     */
    public function transform($value)
    {
        if (null === $value) {
            return 'no User selected';
        }
        if (!$value instanceof User) {
            throw new \LogicException('The UserSelectTextType can only be used with User objects');
        }
        return $value->getEmail();
    }

    /**
     * @var string $value
     */
    public function reverseTransform($value)
    {
        // replaced by callback for flexibility
        // $user = $this->userRepository->findOneBy(['id' => $value]);

        $user = ($this->itsMyFinderCallback)($this->userRepository, $value);
        $users = $this->userRepository->findAll();

        if (!$user) {
            $msg = sprintf('No user found with id "%s", inside %s', $value, 
                join(',',array_map(function($v){return $v->getId();}, $users))
            );
            $failure = new TransformationFailedException();
            $failure->setInvalidMessage($msg);

            throw $failure;
        }

        return $user;
    }
}
