<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Elecms\ElecmsBundle\Utils\DbMailHelper;

class InstallController extends Controller
{

    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step, Request $request)
    {
        $db = new DbMailHelper();

        $form = $this->createFormBuilder($db)
            ->add('server', 'text')
            ->add('database', 'text')
            ->add('user', 'text')
            ->add('password', 'text')
            ->add('token', 'text')
            ->add('mailhost', 'text', array('required' => false,))
            ->add('mailuser', 'text', array('required' => false,))
            ->add('mailpassword', 'text', array('required' => false,))
            ->add('skip', 'checkbox', array(
                'required' => false,
            ))
            ->add('save', 'submit', array('label' => 'Dalej','attr' => array('class'=>'btn btn-default')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $db->skip ? $db->exportToYml('db') : $db->exportToYml() ;
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
