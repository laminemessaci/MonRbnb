<?php


namespace App\Form\DataTranformer;


use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    public function transform($date)
    {
        if ($date === null) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        //Ici on reçoit une date comme 13/12/1980 $frenchDate

        if ($frenchDate === null) {
            throw new TransformationFailedException("Vous devez fournir une date !");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        //Si mal formatée comme ( 13/12-1980)
        if ($date === false) {
            throw new TransformationFailedException("Le format de la date n'est pas le bon !");
        }

        return $date;
    }
}