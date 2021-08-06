<?php

namespace HttpKernel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Fundamental\Entity\Question;
use Fundamental\Factory\QuestionFactory;
use Fundamental\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class QuestionNewController extends AbstractController
{
    public function __invoke()
    {
        return new Response('From invoke!');
    }

    // ua set from UserAgentSubscriber
    public function indexAction(QuestionRepository $questionRepo, $id = null, $plurial = null, $ua, $fromResolver, HttpKernelInterface $httpKernel)
    {
        $questionRepo = $this->getDoctrine()->getRepository(QuestionRepository::NAME);

        /** @var QuestionRepository $questionRepo */
        if (!$question = $questionRepo->findOneBySlug('slug')) {
            $question = QuestionFactory::new()->create();
        }

        $question = $questionRepo->findOneBySlug('slug');

        // FAKE REQUEST
        $req = new Request();
        $req->attributes->set('_controller', 'App\Controller\QuestionController::PartialRequest');
        $req->server->set('REMOTE_ADDR', '127.0.0.1');
        $from = Request::createFromGlobals();
        $resp = $httpKernel->handle($req, HttpKernelInterface::SUB_REQUEST);
        // -

        return $this->render('@HttpKernel/index.html.twig', [
            'question' => $question ? $question->getComment() : 'none',
            'ua' => $ua,
            'fromResolver' => $fromResolver
        ]);
    }

    // Sub-request
    public function partialRequest($partialMac) //$ua, $fromResolver) // will throw error, because only allowed for Master request
    {
        return new Response("Partially rendered! $partialMac");
    }

    public function voteAction(Request $request)
    {
        $request->request->all();
    }
}
