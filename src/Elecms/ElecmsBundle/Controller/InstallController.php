<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Elecms\ElecmsBundle\Utils\InstallDbHelper;

class InstallController extends Controller
{

    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step, Request $request)
    {
        $db = new InstallDbHelper();

        $form = $this->createFormBuilder($db)
            ->add('server', 'text')
            ->add('database', 'text')
            ->add('user', 'text')
            ->add('password', 'text')
            ->add('save', 'submit', array('label' => 'Dalej','attr' => array('class'=>'btn btn-default')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $db->exportParams();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plik nie istnieje, bądź jego uprawnienia nie są wystarczające.<br>Komunikat błędu: <br>'.$e->getMessage());
            }
        }

        return $this->render('ElecmsBundle:Install:step1.html.twig', array(
            'step' => $step,
            'form' => $form->createView(),
        ));
    }

}
