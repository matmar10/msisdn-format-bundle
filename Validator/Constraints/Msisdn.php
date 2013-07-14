<?php

namespace Lmh\Bundle\MsisdnBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Msisdn extends Constraint {

    public $message = "The msisdn '%msisdn%' is invalid for the country '%country%'.";
    
    public function validatedBy()
    {
        return 'msisdn_validator';
    }
}
