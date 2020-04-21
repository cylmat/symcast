<?php

namespace App\Controller\Custom;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BundleEasyAdminController;

class EasyAdminController extends BundleEasyAdminController
{
    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    public function listAction()
    {
        $response = parent::listAction();
        print "Custom listed!";
        return $response;
    }
}