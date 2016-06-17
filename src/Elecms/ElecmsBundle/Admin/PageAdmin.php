<?php

namespace Elecms\ElecmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Elecms\ElecmsBundle\Utils\Helper;

class PageAdmin extends Admin
{
    protected $classnameLabel = 'Pages';
    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'ASC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'number',
    );

    // EDIT FORM
    protected function configureFormFields(FormMapper $formMapper)
    {
        $cp = $this->getConfigurationPool()->getContainer();
        $onepage = $cp->get('db.setting')->get('onepage', true);

            $formMapper
                ->add('title', 'text', array('label' => 'Title'))
                ->add('route', 'text', array('label' => 'Friendly URL',
                    'help' => 'There will be description<br>of routing rules...'))
                ->add('content','textarea', array('attr' => array('class' => 'tinymce')))
                ->add('is_locked',null, array('required' => false, "label" => "Only for logged users?"))
                ->add('is_active',null, array('required' => false, "label" => "Published?"))
                ->add('language', 'entity', array(
                    'class' => 'Elecms\ElecmsBundle\Entity\Language',
                    'choice_label' => 'lang_name',
                ))->add('number', 'choice', array(
                    'choices' => Helper::ListNumber(),
                    'label' => 'Order'
                ))
                ->end();

        if($onepage == 1) {
            $formMapper->with('One page settings');
            $formMapper->add('headerColor', 'text', array('label' => 'Header color',
                'help' => 'Define header color. If You want to use default theme color, leave this field empty',
                'required' => false));
            $formMapper->add('background', 'text', array('label' => 'Background',
                'help' => 'Leave empty for default value. You can add the backround using one of below:<br>
HEX color code ; HTML color name ; Asset path ; URL to image',
                'required' => false));
            $formMapper->add('height', 'text', array('label' => 'Section height (pixels)',
                'help' => 'Set 0 to adjust section height automatically (default)'))
                ->end();
        }


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
            ->add('language')
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
        $container = $this->getConfigurationPool()->getContainer();
        $createdBy = $container->getParameter('Created By');

        $listMapper
            ->addIdentifier('title')
            ->add('language.langName', NULL, array('label' => 'Language'))
            ->add('created_by', NULL, array('label' => $createdBy))
            ->add('modified_by')
            ->add('modified')
            ->add('number', NULL, array('label' => 'Order'))
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