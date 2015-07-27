<?php
namespace Elecms\ElecmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step1Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('server', 'text')
            ->add('database', 'text')
            ->add('user', 'text')
            ->add('password', 'password', array('required' => false))
            ->add('token', 'text')
            ->add('mailhost', 'text', array('required' => false))
            ->add('mailuser', 'text', array('required' => false))
            ->add('mailpassword', 'text', array('required' => false))
            ->add('skip', 'checkbox', array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Elecms\ElecmsBundle\Utils\DbMailHelper'
        ));
    }

    public function getName()
    {
        return 'step1_form'; //Set the "name" attribute of <form> tag
    }
}