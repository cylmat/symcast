<?php

namespace DoctrineQuery\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DoctrineQuery\Entity\Category;
use DoctrineQuery\Entity\FortuneCookie;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

class DoctrineFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cookie1 = (new FortuneCookie())
            ->setFortune("You'll be lucky");

        $cookie2 = (new FortuneCookie())
            ->setFortune("Be glad");

        $coll = new PersistentCollection($manager, FortuneCookie::class, new ArrayCollection([$cookie1, $cookie2]));

        $category = (new Category())
            ->setName('MyCat')
            ->setIconKey('trente_icon')
            ->setFortuneCookies($coll);

        $cookie1->setCategory($category);
        $cookie2->setCategory($category);

        // Cookies are "cascade" persisted
        $manager->persist($category);
        $manager->flush();
    } 
}