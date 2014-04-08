<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Msisdn extends Constraint {

    public $message = "The msisdn '%msisdn%' is invalid for the country '%country%'.";

    public $notInstanceOfMsisdnMessage = '%msisdn% is not an instance Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnInterface';
    
    public function validatedBy()
    {
        return 'msisdn_validator';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
