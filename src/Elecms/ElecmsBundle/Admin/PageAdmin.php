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
            ->add('content','textarea', array('attr' => array('class' => 'tinymce')))
            ->add('is_locked',null, array('required' => false, "label" => "Only for logged users?"))
            ->add('is_active',null, array('required' => false, "label" => "Published?"))
        ;
    }

    // FILTERS
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('created_by')
            ->add('created' ,  'doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_range_picker','format'=>'dd-MMMM-yy'))
            ->add('modified_by')
            ->add('modified',  'doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_range_picker','format'=>'dd-MMMM-yy'))

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
                $page->setCreatedBy($objUser->getId());
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
                $page->setModifiedBy($objUser->getId());
            }
        }

    }
}