<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Service;

use Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnInterface;
use Matmar10\Bundle\MsisdnFormatBundle\Service\MsisdnFactory;
use Matmar10\Bundle\MsisdnFormatBundle\Service\MsisdnFormatConfigurationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Yaml\Parser;

class MsisdnValidator extends ConstraintValidator
{

    public static $configurationService;
    public static $msisdnFactory;

    public function __construct(MsisdnFormatConfigurationService $configurationService, MsisdnFactory $msisdnFactory)
    {
        self::$configurationService = $configurationService;
        self::$msisdnFactory = $msisdnFactory;
    }

    public function validate($msisdn, Constraint $constraint)
    {
        /* @var $msisdn  \Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnInterface */
        /* @var $constraint \Matmar10\Bundle\MsisdnFormatBundle\Validator\Constraints\Msisdn */
        if (!($msisdn instanceof MsisdnInterface)) {
            $this->context->addViolation(
                $constraint->notInstanceOfMsisdnMessage,
                array(
                    '%msisdn%' => $msisdn,
                    '%country%' => 'unknown',
                ),
                $msisdn
            );
            return;
        }

        $msisdnFormat = $msisdn->getMsisdnFormat();
        $msisdnValue = $msisdn->getMsisdn();
        $countryRegexPossibilities = $msisdnFormat->getFormats();

        foreach($countryRegexPossibilities as $regex) {
            // preg_match returns the number of matches, 0 indicates no matches
            if(false !== preg_match($regex, $msisdnValue)) {
                return;
            }
        }

        $this->context->addViolation(
            $constraint->message,
            array(
                '%msisdn%' => $msisdnValue,
                '%country%' => $msisdnFormat->getCountry(),
            ),
            $msisdn
        );
    }
}
