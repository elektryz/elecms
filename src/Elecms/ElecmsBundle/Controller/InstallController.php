<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Session\Session;
use Elecms\ElecmsBundle\Form\Step1Form;
use Elecms\ElecmsBundle\Form\Step2Form;
use Elecms\ElecmsBundle\Form\Step3Form;
use Elecms\ElecmsBundle\Utils\DbMail;
use Elecms\ElecmsBundle\Utils\Helper;
use Elecms\ElecmsBundle\Utils\SettingData;
use Elecms\ElecmsBundle\Entity\UserElecms;
use Elecms\ElecmsBundle\Entity\Setting;


class InstallController extends Controller
{

    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step, Request $request)
    {
        $session = new Session();

        switch($step)
        {
            case 1:
                return $this->step1($request, $session);
                break;
            case 2:
                if($session->get('steps_finished') < 1) {
                    return $this->render('ElecmsBundle:Install:access_denied.html.twig');
                } else {
                    return $this->step2($request, $session);
                }
                break;
            case 3:
                if($session->get('steps_finished') < 2) {
                    return $this->render('ElecmsBundle:Install:access_denied.html.twig');
                } else {
                    return $this->step3($request, $session);
                }
                break;
            case 4:
                if($session->get('steps_finished') < 3) {
                    return $this->render('ElecmsBundle:Install:access_denied.html.twig');
                } else {
                    return $this->step4();
                }
                break;
            default:
                throw $this->createNotFoundException();
        }
    }

    private function step1(Request $request, Session $session)
    {
        $db = new DbMail();

        $form = $this->createForm(new Step1Form(), $db);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if ($form->isValid()) {
                $session->set('steps_finished', '1');
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

    private function step2(Request $request, Session $session)
    {
        // We've got Db params, so let's try to create tables from our Entities
        try {
            $this->createSchema();
        } catch(\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        $admin = new UserElecms();

        $form = $this->createForm(new Step2Form(), $admin);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if ($form->isValid()) {

                $admin->setUsername($admin->getUsername());
                $admin->setPlainPassword($form->get('password')->getData());
                $admin->setEmail($admin->getEmail());
                $admin->setEnabled(true);
                $admin->setSuperAdmin(true);

                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($admin);
                    $em->flush();
                } catch (\PDOException $e) {
                    $this->addFlash('error', 'Database error: '.$e->getMessage());
                }

                $session->set('steps_finished', '2');
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

    private function step3(Request $request, Session $session)
    {
        $setting = new SettingData();

        $form = $this->createForm(new Step3Form(), $setting);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            try {
                $title = new Setting();
                $title->setSettingKey('website_title');
                $title->setSettingValue($setting->getTitle());
                $em = $this->getDoctrine()->getManager();
                $em->persist($title);
                $em->flush();

                $tags = new Setting();
                $tags->setSettingKey('website_tags');
                $tags->setSettingValue($setting->getTags());
                $em = $this->getDoctrine()->getManager();
                $em->persist($tags);
                $em->flush();

                $desc = new Setting();
                $desc->setSettingKey('website_description');
                $desc->setSettingValue($setting->getDescription());
                $em = $this->getDoctrine()->getManager();
                $em->persist($desc);
                $em->flush();

            } catch (\PDOException $e) {
                $this->addFlash('error', 'Database error: '.$e->getMessage());
            }

            $session->set('steps_finished', '3');
            return $this->redirectToRoute('elecms_step', array('step' => 4));
        }

        return $this->render('ElecmsBundle:Install:step3.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function step4()
    {
        return $this->render('ElecmsBundle:Install:step4.html.twig');
    }

    private function createSchema()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:create'
        ));
        $output = new NullOutput();
        $application->run($input, $output);
    }


    }
