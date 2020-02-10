<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{

    /**
     *
     *
     * @param mixed $date The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($date)
    {
        // TODO: Implement transform() method.
        if ($date === null){
            return '';
        }
        return $date->format('d/m/Y');
    }

    /**
     *
     *
     * @param mixed $frenchDate The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($frenchDate)
    {
        // TODO: Implement reverseTransform() method.
        if($frenchDate === null){
            throw new TransformationFailedException("Vous devez fournir une date !");
        }
        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if($date === false){
            throw new TransformationFailedException("LE format de la date est incorrect !");
        }

        return $date ;
    }
}