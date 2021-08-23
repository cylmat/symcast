<?php

namespace DoctrineQuery\Controller;

use DoctrineQuery\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FortuneController extends AbstractController
{
    /**
     * @Route("/fortune", name="homepage_fortune")
     */
    public function homepageAction(Request $request)
    {
        $categoryRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('DoctrineQuery:Category');

        if ($search = $request->query->get('q')) {
            /** @var CategoryRepository $categoryRepository */
            $categories = $categoryRepository->search($search);
        } else {
            /** @var CategoryRepository $categoryRepository */
            $categories = $categoryRepository->findAllOrdered();
        }

        // @Bundle set in Twig\FilesystemLoader
        // and TwigBundle\TwigExtension::load ->YyyBundle to Yyy
        return $this->render('@DoctrineQuery/fortune/homepage.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/fortune/category/{id}", name="category_fortune")
     */
    public function showCategoriesAction(Request $request, CategoryRepository $categoryRepository, string $id)
    {
        $category = $categoryRepository->findWithFortunesJoin($id);

        $fortunesData = $this->getDoctrine()
            ->getRepository('DoctrineQuery:FortuneCookie')
            ->countNumberPrintedForCategory($category);

        $fortunesPrinted = $fortunesData['fortunesPrinted'];
        $averagePrinted = $fortunesData['fortunesAverage'];
        $categoryName = $fortunesData['name'];

        return $this->render('@DoctrineQuery/fortune/categories.html.twig', [
            'categories' => [$category],
            'fortunesPrinted' => $fortunesPrinted,
            'averagePrinted' => $averagePrinted,
            'categoryName' => $categoryName
        ]);
    }
}    
