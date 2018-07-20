<?php
    // src/AppBundle/Form/RegistrationType.php

    namespace AppBundle\Form;

    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    class RegistrationType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('nom');
            $builder->add('prenom');
            $builder->add('cin');
            $builder->add('date_de_naissance', DateType::class, array(
            'widget' => 'single_text', 'format' => 'yyyy-MM-dd'));
        }

        public function getParent()
        {
            return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        }

        public function getBlockPrefix()
        {
            return 'app_user_registration';
        }
    }