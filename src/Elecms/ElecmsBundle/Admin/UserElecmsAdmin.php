<?php

namespace Elecms\ElecmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class UserElecmsAdmin extends Admin
{
    // EDIT FORM
    protected function configureFormFields(FormMapper $formMapper)
    {

        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $rolesChoices = self::flattenRoles($roles);


        unset($rolesChoices["ROLE_SONATA_ADMIN"]); // Hide sonata admin, as we don't have to display that role

        $formMapper
            ->add('email', 'text', array('label' => 'E-mail'))
            ->add('username', 'text')
            ->add('password','password', array('required' => false))
            ->add('enabled', null, array('required' => false))
            ->add('roles', 'choice', array(
                'choices'  => $rolesChoices,
                'multiple' => true
            ))
        ;

    }

    // FILTERS
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $rolesChoices = self::flattenRoles($roles);

        $rolesChoices[""] = $rolesChoices["ROLE_USER"];
        unset($rolesChoices["ROLE_USER"]);

        unset($rolesChoices["ROLE_SONATA_ADMIN"]); // Hide sonata admin as we don't have to display that role


        $datagridMapper
            ->add('email')
            ->add('username')
            ->add('roles', 'doctrine_orm_string',
                array(),
                'choice',
                array('choices' => $rolesChoices
                ))
        ;
    }

    // LIST
    protected function configureListFields(ListMapper $listMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $rolesChoices = self::flattenRoles($roles);
        unset($rolesChoices["ROLE_SONATA_ADMIN"]);


        $listMapper
            ->addIdentifier('email', null, array('label' => 'E-mail'))
            ->add('username')
            ->add('lastLogin')
            ->add('roles','user_roles')
        ;
    }

    public function getExportFields()
    {
        return array('email','username','lastLogin');
    }

    protected static function flattenRoles($rolesHierarchy)
    {
        $flatRoles = array();
        foreach($rolesHierarchy as $roles) {

            if(empty($roles)) {
                continue;
            }

            foreach($roles as $role) {
                if(!isset($flatRoles[$role])) {
                    $flatRoles[$role] = substr($role,5);
                }
            }
        }

        return $flatRoles;
    }


}