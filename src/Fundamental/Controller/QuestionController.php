<?php

namespace Fundamental\Controller;

use Fundamental\Entity\Question;
use Fundamental\Factory\QuestionFactory;
use Fundamental\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    public function indexAction(QuestionRepository $questionRepo, bool $isDebug, LoggerInterface $mdLogger)
    {
        $questionRepo = $this->getDoctrine()->getRepository(QuestionRepository::NAME);

        /** @var QuestionRepository $questionRepo */
        if (!$question = $questionRepo->findOneBySlug('slug')) {
            $question = QuestionFactory::new()->create();
        }

        $question = $questionRepo->findOneBySlug('slug');

        return $this->render('@Fundamental/index.html.twig', [
            'question' => $question ? $question->getComment() : 'none'
        ]);
    }

    public function voteAction(Request $request)
    {
        $request->request->all();
    }
}
