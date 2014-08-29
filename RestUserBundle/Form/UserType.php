<?php

namespace RestUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupid')
            ->add('username')
            ->add('usernameCanonical')
            ->add('fullname')
            ->add('email')
            ->add('emailCanonical')
            ->add('phone')
            ->add('department')
            ->add('salt')
            ->add('password')
            ->add('plainPassword')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RestUserBundle\Entity\User',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        //https://github.com/FriendsOfSymfony/FOSRestBundle/issues/433
        return 'user';
    }
}
