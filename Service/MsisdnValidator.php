<?php

namespace Lmh\Bundle\MsisdnBundle\Service;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Yaml\Parser;
use Lmh\Bundle\MsisdnBundle\Service\MsisdnFormatConfigurationService;
use Lmh\Bundle\MsisdnBundle\Service\MsisdnFactory;

class MsisdnValidator extends ConstraintValidator
{

    public static $configurationService;
    public static $msisdnFactory;

    public function __construct(MsisdnFormatConfigurationService $configurationService, MsisdnFactory $msisdnFactory)
    {
        self::$configurationService = $configurationService;
        self::$msisdnFactory = $msisdnFactory;
    }

    public function isValid($msisdn, Constraint $constraint)
    {

        $msisdnFormat = $msisdn->getMsisdnFormat();

        $country = $msisdnFormat->getCountry();

        $msisdnValue = $msisdn->getMsisdn();

        $countryRegexPossibilities = $msisdnFormat->getFormats();

        foreach($countryRegexPossibilities as $regex) {
            if(false != preg_match($regex, $msisdnValue)) {
                return true;
            }
        }

        $this->setMessage($constraint->message, array(
            '%msisdn%' => $msisdnValue,
            '%country%' => $country,
        ));

        return false;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
