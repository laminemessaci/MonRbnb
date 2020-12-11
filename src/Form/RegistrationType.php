<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom', "Votre prénom ..."))
            ->add('lastName', TextType::class, $this->getConfiguration('Nom', "Votre nom de famille ..."))
            ->add('email', EmailType::class, $this->getConfiguration('Email', "Votre adresse email ..."))
            ->add('picture', UrlType::class, $this->getConfiguration('Photo', "Url de votre avatar ..."))
            ->add('hash', PasswordType::class, $this->getConfiguration('Password', "Votre mot de passe ..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration('Confimation mot de passe', "Veillez retaper votre mot de pass  ..."))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', "Présentez vous en quelques mots ..."))
            ->add('description', TextareaType::class, $this->getConfiguration('Déscription détaillée', "présentez vous ici ..."))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
