<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * Permet d'avoir une configuration de base'
     *
     * @param $label string
     * @param $placeholder string
     * @param  $options array
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ]
        ], $options);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                 $this->getConfiguration("Titre", "Tapez un super titre pour votre annonce"
                ))
            ->add(
                'slug',
                TextType::class,
                 $this->getConfiguration("Adresse Web", "Tapez l'adresse web (automatique)"
                 , $options = ['required' => false]))
            ->add(
                'coverImage',
                UrlType::class,
                 $this->getConfiguration("Url de l'image principal", "Donné l'adresse d'une image qui donne vraiment envie"
                 ))
            ->add(
                'introduction',
                TextType::class,
                 $this->getConfiguration("Introduction", "Donnez un description globale pour votre annonce"
                 ))
            ->add(
                'description',
                TextareaType::class,
                 $this->getConfiguration("Description détaillée", "Tapez une description qui donne envie de venir chez vous"
                ))
            ->add(
                'rooms',
                IntegerType::class,
                 $this->getConfiguration("Nombre de chambres disponible", "Le nombre de chambre disponible"
                 ))
            ->add(
                'price',
                MoneyType::class,
                 $this->getConfiguration("Prix par nuit", "Indiquez le prix que vous voulez pour une nuit"
                 ))
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            )

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
