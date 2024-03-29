<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{

    /**
     * Permet de voir la configuration de base d'un champ!
     * @param $label
     * @param $placeholder
     * @param $options
     * @return array
     */
    protected function getConfiguration($label, $placeholder,  $options = [])
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ]
        ], $options);
    }
}