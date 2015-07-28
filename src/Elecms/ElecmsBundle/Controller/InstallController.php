<?php

namespace Elecms\ElecmsBundle\Controller;

use Elecms\ElecmsBundle\Form\Step1Form;
use Elecms\ElecmsBundle\Form\Step2Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Elecms\ElecmsBundle\Utils\DbMail;
use Elecms\ElecmsBundle\Utils\Helper;
use Elecms\ElecmsBundle\Entity\UserElecms;


class InstallController extends Controller
{

    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step, Request $request)
    {
        switch($step)
        {
            case 1:
                return $this->step1($request);
                break;
            case 2:
                return $this->step2($request);
                break;
            default:
                throw $this->createNotFoundException();
        }
    }

    private function step1(Request $request)
    {
        $db = new DbMail();

        $form = $this->createForm(new Step1Form(), $db);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if ($form->isValid()) {
                return $this->redirectToRoute('elecms_step', array('step' => 2));
            } else {
                $validator = $this->get('validator');
                $error = $validator->validate($db);
                $this->addFlash('error', Helper::RenderErrors($error));
            }
         }

        return $this->render('ElecmsBundle:Install:step1.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function step2(Request $request)
    {
        $admin = new UserElecms();

        $form = $this->createForm(new Step2Form(), $admin);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if ($form->isValid()) {
                $admin->setUsername($form->get('username')->getData());
                $admin->setPlainPassword($form->get('password')->getData());
                $admin->setEmail($form->get('email')->getData());
                $admin->setEnabled(true);
                $admin->setSuperAdmin(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($admin);
                $em->flush();

                return $this->redirectToRoute('elecms_step', array('step' => 3));

            } else {
                $validator = $this->get('validator');
                $error = $validator->validate($admin);

                $this->addFlash('error', Helper::RenderErrors($error));
            }
        }

        return $this->render('ElecmsBundle:Install:step2.html.twig', array(
            'form' => $form->createView()
        ));
    }

    }
