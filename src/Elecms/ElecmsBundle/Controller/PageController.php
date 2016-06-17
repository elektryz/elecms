<?php

namespace Elecms\ElecmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->createQuery('SELECT p FROM Elecms\ElecmsBundle\Entity\Page p
                                    ORDER BY p.number ASC')->getResult();

        $dbSetting = $this->get('db.setting');
        $onepage = $dbSetting->get('onepage');

        return $this->render('ElecmsBundle:Page:index.html.twig', array(
            'pages' => $content,
            'is_one_page' => $onepage,
            'title' => $dbSetting->get('website_title'),
            'description' => $dbSetting->get('website_description'),
        ));
    }

    public function renderAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('ElecmsBundle:Page')->findOneBy(array('route' => $page));

        $dbSetting = $this->get('db.setting');
        $onepage = $dbSetting->get('onepage');

        return $this->render('ElecmsBundle:Page:index.html.twig', array(
            'content' => $content->getContent(),
            'is_locked' => $content->getIsLocked(),
            'is_one_page' => $onepage,
            'title' => $dbSetting->get('website_title'),
        ));
    }
}