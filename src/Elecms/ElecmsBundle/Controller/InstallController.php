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

        if($request->isMethod('POST'))
        {
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

        if($session->get('username'))
            $admin->setUsername($session->get('username'));

        if($session->get('email'))
            $admin->setEmail($session->get('email'));

        $form = $this->createForm(new Step2Form(), $admin);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if ($form->isValid()) {

                $session->set('username', $admin->getUsername());
                $session->set('email', $admin->getEmail());

                $em = $this->getDoctrine()->getManager();

                $db_admin = $em->getRepository('ElecmsBundle:UserElecms')->findOneBy(
                    array(
                        'email' => $admin->getEmail()
                    )
                );

                $userManager = $this->container->get('fos_user.user_manager');

                // If user edits email which was already saved to database, perform an update
                if($db_admin) {
                    $db_admin->setUsername($admin->getUsername());
                    $db_admin->setPlainPassword($form->get('password')->getData());
                    $userManager->updatePassword($db_admin);
                    $db_admin->setEmail($admin->getEmail());
                    $db_admin->setEnabled(true);
                    $db_admin->setSuperAdmin(true);
                    $db_admin->setAdmin(true);
                    $em->flush();

                // Otherwise, insert a new row
                } else {
                    $admin->setUsername($admin->getUsername());
                    $admin->setPlainPassword($form->get('password')->getData());
                    $userManager->updatePassword($admin);
                    $admin->setEmail($admin->getEmail());
                    $admin->setEnabled(true);
                    $admin->setSuperAdmin(true);
                    $admin->setAdmin(true);
                    try {
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($admin);
                        $em->flush();
                    } catch (\PDOException $e) {
                        $this->addFlash('error', 'Database error: '.$e->getMessage());
                    }
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
                $em = $this->getDoctrine()->getManager();
                $website_title = $em->getRepository('ElecmsBundle:Setting')->findOneBy(
                    array(
                        'settingKey' => 'website_title'
                    )
                );

                if (!$website_title) {
                    throw $this->createNotFoundException(
                        'Setting key website_title was not found in setting table'
                    );
                }

                $website_tags = $em->getRepository('ElecmsBundle:Setting')->findOneBy(
                    array(
                        'settingKey' => 'website_tags'
                    )
                );

                if (!$website_tags) {
                    throw $this->createNotFoundException(
                        'Setting key website_tags was not found in setting table'
                    );
                }

                $website_description = $em->getRepository('ElecmsBundle:Setting')->findOneBy(
                    array(
                        'settingKey' => 'website_description'
                    )
                );

                if (!$website_description) {
                    throw $this->createNotFoundException(
                        'Setting key website_description was not found in setting table'
                    );
                }

                $website_title->setSettingValue($form->get('title')->getData());
                $website_title->setModified(new \DateTime());

                $website_tags->setSettingValue($form->get('tags')->getData());
                $website_tags->setModified(new \DateTime());

                $website_description->setSettingValue($form->get('description')->getData());
                $website_description->setModified(new \DateTime());
                $website_title->setFieldType('textarea');

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
