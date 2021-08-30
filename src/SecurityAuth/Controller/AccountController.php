<?php

namespace SecurityAuth\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SecurityAuth\Entity\Article;
use SecurityAuth\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/** 
 * @IsGranted("ROLE_USER_LAMBDA")
 * 
 * @method User|null getUser()
 */
class AccountController extends AbstractController
{
    private $security;

    /**
     * @Route("/account", name="security_account")
     */
    public function account(LoggerInterface $logger, Security $security) //can be used in services
    {
        $this->security = $security;

        $logger->withName('security_service')->info('user', [
            'user' => $this->security->getUser(),
            'is_granted' => $this->security->isGranted('IS_AUTHENTICATED_FULLY'),
        ]);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); // no guests, only authenticated users

        $user = $this->getUser();
        $logger->withName('my_own')->debug($user->getEmail(), ['alpha']);

        return $this->render('@SecurityAuth/account.html.twig', []);
    }



    ////////////////// in article controller
    
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/article/new", name="admin_article_new")
     */
    public function new()
    {   
    }

    /**
     * @Route("/article/show", name="admin_article_show")
     */
    public function show(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository('SecurityAuth:Article')->findAll();

        d($articles);
        return new Response('');
    }

    /**
     * Manually control access
     * 
     * @Route("/article/{id}/edit")
     */
    public function edit(Article $article)
    {
        if ($article->getAuthor() != $this->getUser() && !$this->isGranted('ROLE_ADMIN_ARTICLE')) {
            throw $this->createAccessDeniedException('No access!');
        }

        dd($article);
    }

    /**
     *          VOTERS
     * 
     * Two by default:
     *  RoleVoter ("ROLE_...") and AuthenticatedVoter ("IS_AUTHENTICATED_...")
     * 
     * @Route("/article/{id}/editwith")
     * 
     * ...can use @IsGranted("MANAGE", subject="article")
     */
    public function editWithVoters(Article $article)
    {
        // ...or use
        $this->denyAccessUnlessGranted('MANAGE', $article);

        if (!$this->isGranted('MY_MANAGE', $article)) {
            dd($article);
        }
    }
}