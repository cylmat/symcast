<?php

namespace UseBundle\Controller;

use KnpBundle\Service\KnpIpsum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $isDebug;
    private $knpUIpsum;

    public function __construct(bool $isDebug, KnpIpsum $knpIpsum)
    {
        $this->isDebug = $isDebug;
        $this->knpIpsum = $knpIpsum;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('@UseBundle/article/homepage.html.twig', [
            'parags' => $this->knpIpsum->getParagraphs()
        ]);
    }

}
