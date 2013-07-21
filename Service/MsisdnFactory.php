<?php

namespace Lmh\Bundle\MsisdnBundle\Service;

use Lmh\Bundle\MsisdnBundle\Entity\Msisdn;
use Lmh\Bundle\MsisdnBundle\Exception\InvalidFormatException;
use Lmh\Bundle\MsisdnBundle\Service\MsisdnFormatConfigurationService;
use Lmh\Bundle\MsisdnBundle\Validator\Constraints\Msisdn as MsisdnConstraint;
use Symfony\Component\Validator\ValidatorInterface;

class MsisdnFactory
{

    protected static $configurationService;

    protected static $validator;

    public function __construct(
        MsisdnFormatConfigurationService $configurationService,
        ValidatorInterface $validator)
    {
        self::$configurationService = $configurationService;
        self::$validator = $validator;
    }

    /**
     * Builds a msisdn object for the specified country based on the provided mobile number
     *
     * @param string $country The country to build the msisdn for
     * @param string $mobileNumber The mobile number or msisdn to build from
     * @param bool $isMsisdn Whether the supplied number is already a msisdn
     * @return \Lmh\Bundle\MsisdnBundle\Entity\Msisdn The constructed msisdn
     * @throws \Lmh\Bundle\MsisdnBundle\Exception\InvalidFormatException
     */
    public function get($country, $mobileNumber, $isMsisdn = false)
    {
        $msisdnFormat = self::$configurationService->get($country);
        $msisdn = new Msisdn();
        $msisdn->setMsisdnFormat($msisdnFormat);

        if($mobileNumber instanceof Msisdn) {
            $msisdn->setMsisdn($msisdn->getMsisdn());
        } elseif($isMsisdn) {
            $msisdn->setMsisdn($mobileNumber);
        } else {

            // strip out non digits
            $mobileNumber = preg_replace('/[^0-9]/', '', $mobileNumber);

            // strip the national dialing prefix, if it exists (since it's not part of a msisdn)
            $nationalDialingPrefix = $msisdnFormat->getNationalDialingPrefix();
            $stripped = $mobileNumber;
            if(strlen($nationalDialingPrefix)) {
                if(0 === strpos($mobileNumber, $nationalDialingPrefix)) {
                    $stripped = substr($mobileNumber, strlen($nationalDialingPrefix));
                }
            }

            // finally, prepend the international prefix
            $msisdnValue = $msisdnFormat->getInternationalPrefix() . $stripped;
            $msisdn->setMsisdn($msisdnValue);
        }

        $msisdnConstraint = new MsisdnConstraint();
        $constraintViolationList = self::$validator->validateValue($msisdn, $msisdnConstraint);
        if(count($constraintViolationList)) {
            $messageFormat = "Cannot build a '%s' msisdn from number '%s'.";
            $message = sprintf($messageFormat, $country, $mobileNumber, $msisdnFormat->getExampleMobile());
            throw new InvalidFormatException($message);
        }

        return $msisdn;
    }

}
