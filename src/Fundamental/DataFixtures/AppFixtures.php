<?php

namespace Fundamental\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fundamental\Entity\Question;
use Fundamental\Factory\QuestionFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $question = QuestionFactory::new()
            ->unpublished()
            ->create(); // createMany(20)
        
        $manager->persist($question->object());
        $manager->flush();
    }
}
