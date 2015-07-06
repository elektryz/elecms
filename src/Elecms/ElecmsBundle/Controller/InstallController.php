<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elecms\ElecmsBundle\Utils\DbParams;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step, Request $request)
    {

        $db = new DbParams();
        $form = $this->createFormBuilder($db)
            ->add('server', 'text')
            ->add('database', 'text')
            ->add('user', 'text')
            ->add('password', 'text')
            ->add('save', 'submit', array('label' => 'Dalej','attr' => array('class'=>'btn btn-default')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $db->setServer('Server');
            $db->setDatabase('database');
            $db->setUser('user');
            $db->setPassword('password');

            //return $this->redirectToRoute('task_success');
        }



        return $this->render('ElecmsBundle:Install:step1.html.twig', array(
            'step' => $step,
            'form' => $form->createView(),
        ));
    }

}
