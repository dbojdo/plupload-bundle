<?php

namespace Webit\Common\PlUploadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WebitCommonPlUploadBundle:Default:index.html.twig', array('name' => $name));
    }
}
