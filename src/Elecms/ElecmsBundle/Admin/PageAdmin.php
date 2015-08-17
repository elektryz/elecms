<?php

namespace Elecms\ElecmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PageAdmin extends Admin
{
    // EDIT FORM
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text', array('label' => 'Title'))
            ->add('route', 'text', array('label' => 'Friendly URL',
                'help' => 'There will be description<br>of routing rules...'))
            ->add('content','textarea', array('attr' => array('class' => 'tinymce')))
            ->add('is_locked',null, array('required' => false, "label" => "Only for logged users?"))
            ->add('is_active',null, array('required' => false, "label" => "Published?"))
        ;
    }

    // FILTERS
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $repo = $this->getConfigurationPool()->getContainer()
            ->get('doctrine')->getManager()->getRepository('Elecms\ElecmsBundle\Entity\UserElecms')
            ->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :super_admin')
            ->orWhere('u.roles LIKE :admin')
            ->setParameter('super_admin', '%ROLE_SUPER_ADMIN%')
            ->setParameter('admin', '%ROLE_ADMIN%')
            ->getQuery()
            ->getResult();

        $usersList = array();
        foreach($repo as $user)
        {
                $usersList[$user->getUsername()] = $user->getUsername();
        }

        $datagridMapper
            ->add('title')
            ->add('created_by', 'doctrine_orm_string',
                array(),
                'choice',
                array('choices' => $usersList
                ))
            ->add('modified_by', 'doctrine_orm_string',
                array(),
                'choice',
                array('choices' => $usersList
                ))
        ;
    }

    // LIST
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('created_by')
            ->add('created')
            ->add('modified_by')
            ->add('modified')
        ;
    }

    public function prePersist($page)
    {
        parent::prePersist($page);

        $isSuperAdmin = $this->getConfigurationPool()->getContainer()->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $isAdmin = $this->getConfigurationPool()->getContainer()->get('security.context')->isGranted('ROLE_ADMIN');
        if($isAdmin || $isSuperAdmin) {
            $objUser = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
            if(!empty($objUser)) {
                $page->setCreatedBy($objUser->getUsername());
            }
        }

    }

    public function preUpdate($page)
    {
        parent::prePersist($page);

        $isSuperAdmin = $this->getConfigurationPool()->getContainer()->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $isAdmin = $this->getConfigurationPool()->getContainer()->get('security.context')->isGranted('ROLE_ADMIN');
        if($isAdmin || $isSuperAdmin) {
            $objUser = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
            if(!empty($objUser)) {
                $page->setModifiedBy($objUser->getUsername());
            }
        }

    }
}