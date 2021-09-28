<?php

namespace SecurityAuth\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * Will user API Tokens
     * 1. Created manually
     * 2. Call an API endpoint to retrieve tokens
     * 3. Using OAuth2
     */

    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['serialize_main'], //Serializer annotations
        ]);
    }
}