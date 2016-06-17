<?php
namespace Elecms\ElecmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step3Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => false))
            ->add('tags', 'textarea', array('required' => false,
                'attr' => array('rows' => '4')))
            ->add('description', 'textarea', array('required' => false,
                'attr' => array('rows' => '4')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Elecms\ElecmsBundle\Form\SettingData',
        ));
    }

    public function getName()
    {
        return 'step3_form'; //Set the "name" attribute of <form> tag
    }
}