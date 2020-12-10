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
     * Permet de voir la configuration de base d'un champ!
     * @param $label
     * @param $placeholder
     * @param $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Tapez un titre pour votre annonce'))
            ->add('slug', TextType::class, $this->getConfiguration('Adresse web', "Tapez l'adresse web(automatique)", [
                'required' => false
            ])
            )
            ->add('coverImage', UrlType::class, $this->getConfiguration('URL de l\'image principale', 'Donnez l\'adresse de votre image'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Donnez une description globale'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description', 'Tapez la description de votre bien'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix', 'Indiquez le prix pour un nuit'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambres', 'Tapez le nombre de chambres disponible'))
            ->add(
                'images',
                CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
