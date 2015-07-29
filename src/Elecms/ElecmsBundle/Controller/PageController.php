<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElecmsBundle:Page:index.html.twig');
    }
}