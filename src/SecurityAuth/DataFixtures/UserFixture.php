<?php

namespace SecurityAuth\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SecurityAuth\Entity\ApiToken;
use SecurityAuth\Entity\Article;
use SecurityAuth\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passEncoder;
    private $users;

    public function __construct(UserPasswordEncoderInterface $passEncoder)
    {
        $this->passEncoder = $passEncoder;   
    }

    public function getDependencies()
    {
        return [
            //zzzFixture::class,
        ];
    }

    public function setApiTokens(ObjectManager $manager, User $user)
    {
        $apiToken1 = new ApiToken($user);
        $apiToken2 = new ApiToken($user);

        $manager->persist($apiToken1);
        $manager->persist($apiToken2);
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(3, function($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setFirstName($this->faker->firstName());
            $user->setPassword($this->passEncoder->encodePassword($user, 'pass'));

            $this->setApiTokens($manager, $user);
            $this->users[] = $user;

            return $user;
        });

        $this->createMany(3, function($i) use ($manager) {
            $article = new Article();
            $article->setAuthor($this->users[rand(0, 1)]);
            $article->setContent($this->faker->sentence());

            return $article;
        });

        /*
            Admin
        */
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setFirstName($this->faker->firstName());
        $user->setPassword($this->passEncoder->encodePassword($user, 'pass'));
        $user->setRoles([User::ROLE_ADMIN]);
        $manager->persist($user);

        $article = new Article();
        $article->setAuthor($user);
        $article->setContent('admin - ' . $this->faker->sentence());
        $manager->persist($article);

        $manager->flush();
    }
}
