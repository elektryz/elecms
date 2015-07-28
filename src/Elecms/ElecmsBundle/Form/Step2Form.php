<?php
namespace Elecms\ElecmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step2Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('email', 'text')
            ->add('password', 'password')
            ->add('password_confirm', 'password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Elecms\ElecmsBundle\Entity\UserElecms',
        ));
    }

    public function getName()
    {
        return 'step2_form'; //Set the "name" attribute of <form> tag
    }
}