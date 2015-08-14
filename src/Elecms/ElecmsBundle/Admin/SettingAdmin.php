<?php
namespace Elecms\ElecmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class SettingAdmin extends Admin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }

    // EDIT FORM
    protected function configureFormFields(FormMapper $formMapper)
    {
        preg_match_all('/\d+/', $_SERVER['REQUEST_URI'], $matches);

        $repo = $this->getConfigurationPool()->getContainer()
            ->get('doctrine')->getManager()->getRepository('Elecms\ElecmsBundle\Entity\Setting')
            ->findOneBy(array('id' => $matches[0]));

        $formMapper
            ->with($repo->getSettingKey())
            ->add('settingValue', $repo->getFieldType(), array('label' => 'Value','required' => false));
    }

    // FILTERS
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('settingKey')
            ->add('settingValue');
    }

    // LIST
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('settingKey')
            ->add('settingValue', null, array('label' => 'Value'))
            ->add('modified', null, array('label' => 'Modified'));
    }
}