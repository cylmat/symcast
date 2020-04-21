<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlphaController extends AbstractController
{
    /**
     * @Route("/alpha", name="alpha")
     */
    public function index(Request $request): Response
    {
        $greet_txt = 
            ($name = $request->query->get('hi')) ? 
            sprintf("Hello dare %s - ", htmlspecialchars($name)) : 
            ''
        ;
        return new Response(
<<<EOF
<html>
$greet_txt inside
</html>
EOF
        );
        return $this->render('alpha/index.html.twig', [
            'controller_name' => 'AlphaController',
        ]);
    }
}
