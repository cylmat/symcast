<?php

namespace Forms\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Forms\Form\ArticleFormType;
use Forms\Form\ArticleFormTypeWithDTO;
use Forms\Model\ArticleFormModelDTO;
use SecurityAuth\Entity\Article;
use SecurityAuth\Repository\ArticleRepository;
use SecurityAuth\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User getUser()
 */
class TryFormController extends AbstractController
{
    /**
     * @Route("/new", name="form_new")
     */ 
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        //null: create
        // for edit, using getters from object
        // $form = $this->createForm(ArticleFormType::class, $article 'from request');
        $form = $this->createForm(ArticleFormType::class, null, [
            'i_want_to_include_publishing' => true //include or not an element
        ]); 

        $form->handleRequest($request); //process only when POST
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Article Created from!');

            // with data 
            $data = $form->getData();
            $article = new Article();
            $article->setTitle($data->getTitle()); //$data['title']);
            $article->setContent($data->getContent());
            // ($user = $this->getUser()) ? $article->setAuthor($user) : null; // called into the form itself
            $unexistedParam = $form['unexistedParam']->getData(); //process data before persist

            // with data mapped
            /**
             * @var Article $data
             */
            $article = $form->getData();
            if (true === $form['agreeTerms']->getData()) {
                $article->agreeTerms();
            }

            $article->setTitle($article->getTitle() . $unexistedParam);
            d($article);
            $em->persist($article);

            // $em->flush();
            // return $this->redirectToRoute('app_homepage');
        }

        return $this->render('@Forms\new.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/new_dto", name="form_new_dto")
     */ 
    public function new_dto(Request $request): Response
    {
        $form = $this->createForm(ArticleFormTypeWithDTO::class, null);

        $form->handleRequest($request); //process only when POST
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Article Created from DTO!');

            /** @var ArticleFormModelDTO $articleFormModel */
            $articleFormModel = $form->getData();

            ///////////////////////////////////////////////// DTO
            $article = new Article();
            $article->setAuthor($articleFormModel->author);
            $article->setContent($articleFormModel->content);
            $article->setTitle($articleFormModel->title . '-' . $articleFormModel->unexistedParam);
            $article->setPublishedAt($articleFormModel->publishedAt);
            
            $articleFormModel->agreeTerms && $article->agreeTerms();
            d($article);
            //////////////////////////////////////////////////////// -dto

            // Persist && flush...
        }

        return $this->render('@Forms\new.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/utility/users", methods="GET", name="admin_utility_users")
     * 
     *      IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function getUsersApi(UserRepository $userRepository, Request $request)
    {
        $users = $userRepository->findAllMatch($request->query->get('query'));

        return $this->json([
            'users_list_values' => $users,
        ], 200, [], [
            'groups'=> ['serialize_main', 'serialize_id']  //Serializer annotations in entity
        ]);
    }

    /**
     * @Route("/article/location-select", name="admin_article_location_select")
     * 
     * needs to be available to anyone that has ROLE_ADMIN_ARTICLE or is the author of at least one article
     *  // IsGranted("ROLE_USER")
     *         // a custom security check
        if (!$this->isGranted('ROLE_ADMIN_ARTICLE') && $this->getUser()->getArticles()->isEmpty()) // at least one article
                no access
                use fetch="EXTRA_LAZY" // to count article without fetching them
     */
    public function getSpecificLocationSelect(Request $request)
    {
        $article = new Article();
        $article->location = $request->query->get('location');

        // Remove "specificLocationName" field if no location selected
        if ('' === $article->location || 'interstellar_space' === $article->location) {
            $article->location = null;
        }

        $form = $this->createForm(ArticleFormType::class, $article);

        // no field? Return an empty response
        if (!$form->has('specificLocationName')) {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        return $this->render('@Forms/_specific_location.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article", name="form_article_list")
     */
    public function list(ArticleRepository $articleRepo)
    {

    }
}
