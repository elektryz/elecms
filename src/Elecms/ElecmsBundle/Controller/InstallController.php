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
use Elecms\ElecmsBundle\Form\SettingData;
use Elecms\ElecmsBundle\Entity\UserElecms;


class InstallController extends Controller
{

    public function indexAction()
    {
        return $this->render('ElecmsBundle:Install:index.html.twig');
    }

    public function stepAction($step, Request $request)
    {
        $session = new Session();

        switch($step) {
            case 1:
                return $this->step1($request, $session);
            case 2:
                if($session->get('steps_finished') < 1)
                    return $this->render('ElecmsBundle:Install:access_denied.html.twig');
                else
                    return $this->step2($request, $session);
            case 3:
                if($session->get('steps_finished') < 2)
                    return $this->render('ElecmsBundle:Install:access_denied.html.twig');
                else
                    return $this->step3($request, $session);
            case 4:
                if($session->get('steps_finished') < 3)
                    return $this->render('ElecmsBundle:Install:access_denied.html.twig');
                else
                    return $this->step4($session);
            default:
                throw $this->createNotFoundException();
        }
    }

    private function step1(Request $request, Session $session)
    {
        $db = new DbMail();

        if($session->get('server'))
            $db->setServer($session->get('server'));

        if($session->get('database'))
            $db->setDatabase($session->get('database'));

        if($session->get('user'))
            $db->setUser($session->get('user'));

        if($session->get('mailhost'))
            $db->setMailhost($session->get('mailhost'));

        if($session->get('mailuser'))
            $db->setMailuser($session->get('mailuser'));

        if($session->get('token'))
            $db->setToken($session->get('token'));

        if($session->get('skip'))
            $db->setSkip($session->get('skip'));

        $form = $this->createForm(new Step1Form(), $db);
        $form->handleRequest($request);

        if($request->isMethod('POST')) {
            if ($form->isValid()) {
                $session->set('steps_finished', '1');
                $session->set('server', $db->getServer());
                $session->set('database', $db->getDatabase());
                $session->set('user', $db->getUser());
                $session->set('mailhost', $db->getMailhost());
                $session->set('mailuser', $db->getMailuser());
                $session->set('token', $db->getToken());
                $session->set('skip', $db->getSkip());

                return $this->redirectToRoute('elecms_step', array('step' => 2));
            } else {
                $validator = $this->get('validator');
                $error = $validator->validate($db);
                $this->addFlash('error', Helper::RenderErrors($error));
            }
        }

        return $this->render('ElecmsBundle:Install:step1.html.twig', array('form' => $form->createView()));
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

        if($session->get('username'))
            $admin->setUsername($session->get('username'));

        if($session->get('email'))
            $admin->setEmail($session->get('email'));

        $form = $this->createForm(new Step2Form(), $admin);
        $form->handleRequest($request);

        if($request->isMethod('POST')) {
            if ($form->isValid()) {
                $userManager = $this->container->get('fos_user.user_manager');

                $dbUser = $this->get('db.user');
                $dbUser->install($admin, $userManager, $form, $session);

                $session->set('steps_finished', '2');
                return $this->redirectToRoute('elecms_step', array('step' => 3));
            } else {
                $validator = $this->get('validator');
                $error = $validator->validate($admin);
                $this->addFlash('error', Helper::RenderErrors($error));
            }
        }

        return $this->render('ElecmsBundle:Install:step2.html.twig', array('form' => $form->createView()));
    }

    private function step3(Request $request, Session $session)
    {
        $form = $this->createForm(new Step3Form(), new SettingData());
        $form->handleRequest($request);

        if($request->isMethod('POST')) {
            $dbSetting = $this->get('db.setting');
            $dbSetting->install($form);

            $session->set('steps_finished', '3');
            return $this->redirectToRoute('elecms_step', array('step' => 4));
        }

        return $this->render('ElecmsBundle:Install:step3.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function step4(Session $session)
    {
        $dbLanguage = $this->get('db.language');
        $dbService = $this->get('db.service');

        $dbLanguage->install();

        if($dbService->checkInstallation()) {
            $session->clear();
            $return = $this->render('ElecmsBundle:Install:step4.html.twig', array('installed' => true));
        }
        else
            $return = $this->render('ElecmsBundle:Install:step4.html.twig');

        return $return;
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
