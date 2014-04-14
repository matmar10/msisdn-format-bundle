<?php

namespace Matmar10\Bundle\BitcoinAddressValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BitcoinAddress extends Constraint {

    public $message = "The bitcoin address '%address%' is not valid.";
    
    public function validatedBy()
    {
        return 'bitcoin_address_validator';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
