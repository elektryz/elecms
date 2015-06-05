<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InstallController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step)
    {
        return $this->render('ElecmsBundle:Install:step.html.twig', array('step' => $step));
    }
}
