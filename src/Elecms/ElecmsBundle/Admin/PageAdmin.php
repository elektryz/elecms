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

    }

    // LIST
    protected function configureListFields(ListMapper $listMapper)
    {

    }
}