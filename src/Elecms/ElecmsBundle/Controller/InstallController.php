<?php

namespace Elecms\ElecmsBundle\Controller;

use Elecms\ElecmsBundle\Form\Step1Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Elecms\ElecmsBundle\Utils\DbMailHelper;
use Elecms\ElecmsBundle\Utils\Helper;


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
            default:
                throw $this->createNotFoundException();
        }
    }

    private function step1($request)
    {
        $db = new DbMailHelper();

        $form = $this->createForm(new Step1Form(), $db);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $db->skip ? $db->exportToYml('db') : $db->exportToYml();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Plik nie istnieje, bądź jego uprawnienia nie są wystarczające.
                <br>Komunikat błędu: <br>'.$e->getMessage());
            }
        } else {
            $validator = $this->get('validator');
            $error = $validator->validate($db);
            $errorsArray = array();

            if (count($error) > 0) {
                foreach($error as $err)
                    $errorsArray[] = Helper::ValidationCleanString((string) $err);

                $errorString = implode("<br>", $errorsArray);
                $this->addFlash('error', $errorString);
            }
        }

        return $this->render('ElecmsBundle:Install:step1.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
