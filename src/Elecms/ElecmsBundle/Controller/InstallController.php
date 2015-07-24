<?php

namespace Elecms\ElecmsBundle\Controller;

use Elecms\ElecmsBundle\Form\Step1Form;
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
        switch($step)
        {
            case 1:
                return $this->step1($step, $request);
                break;
            default:
                throw $this->createNotFoundException();
        }
    }

    private function step1($step, $request)
    {
        $db = new DbMailHelper();

        $form = $this->createForm(new Step1Form(), $db);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $db->skip ? $db->exportToYml('db') : $db->exportToYml();
                try {
                    $pdo = new \PDO("mysql:host={$db->getServer()};dbname={$db->getDatabase()}", $db->getUser(), $db->getPassword());
                    return $this->redirectToRoute('elecms_step', array('step' => 2));
                } catch(\PDOException $e) {
                    $this->addFlash('error', $this->get('translator')->trans('Database parameters are incorrect.'));
                }
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
