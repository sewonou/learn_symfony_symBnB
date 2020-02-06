<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
//use App\form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',
                PasswordType::class,
                $this->getConfiguration("Ancien Mot de passe", "Donnez votre mot de passe actuel...")
            )
            ->add('newPassword',
                PasswordType::class,
                $this->getConfiguration("Nouveau mot de passe", "Tapez votre nouveau mot de passe actuel...")
            )
            ->add('confirmPassword',
                PasswordType::class,
                $this->getConfiguration("Confirmaitioon du Mot de passe", "Confirmez votre mot de passe votre mot de passe actuel")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
