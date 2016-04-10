<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElecmsBundle:Page:index.html.twig');
    }

    public function renderAction($_locale, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('ElecmsBundle:Page')->findOneBy(
            array(
                'route' => $page
            )
        );

        if(!$content)
            throw $this->createNotFoundException('This page does not exist.');

        return $this->render('ElecmsBundle:Page:index.html.twig', array(
            'content' => $content
        ));
    }
}