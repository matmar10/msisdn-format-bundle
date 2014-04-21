<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Service;

use Matmar10\Bundle\MsisdnFormatBundle\Entity\Msisdn;
use Matmar10\Bundle\MsisdnFormatBundle\Exception\InvalidFormatException;
use Matmar10\Bundle\MsisdnFormatBundle\Service\MsisdnFormatConfigurationService;
use Matmar10\Bundle\MsisdnFormatBundle\Validator\Constraints\Msisdn as MsisdnConstraint;
use Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException;
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
     * @param string $mobileNumberOrMsisdn The mobile number or msisdn to build from
     * @param bool $isMsisdn Whether the supplied number is already a msisdn
     * @return Matmar10\Bundle\MsisdnFormatBundle\Entity\Msisdn The constructed msisdn
     * @throws Matmar10\Bundle\MsisdnFormatBundle\Exception\InvalidFormatException
     */
    public function get($country, $mobileNumberOrMsisdn, $isMsisdn = false)
    {
        $msisdn = new Msisdn();
        $msisdnFormat = self::$configurationService->get($country);
        $msisdn->setMsisdnFormat($msisdnFormat);
        $this->setMsisdn($msisdn, $mobileNumberOrMsisdn, $isMsisdn);
        return $msisdn;
    }

    protected function setMsisdn(&$newMsisdn, $mobileOrMsisdn, $isMsisdn)
    {

        if($mobileOrMsisdn instanceof Msisdn) {
            $newMsisdn->setMsisdn($mobileOrMsisdn->getMsisdn());
            return;
        }

        if($isMsisdn) {
            $newMsisdn->setMsisdn($mobileOrMsisdn);
            return;
        }

        $msisdnFormat = $newMsisdn->getMsisdnFormat();

        // strip out non digits
        $mobileOrMsisdn = preg_replace('/[^0-9]/', '', $mobileOrMsisdn);

        // strip the national dialing prefix, if it exists (since it's not part of a msisdn)
        $nationalDialingPrefix = $msisdnFormat->getNationalDialingPrefix();
        $stripped = $mobileOrMsisdn;
        if(strlen($nationalDialingPrefix)) {
            if(0 === strpos($mobileOrMsisdn, $nationalDialingPrefix)) {
                $stripped = substr($mobileOrMsisdn, strlen($nationalDialingPrefix));
            }
        }

        // finally, prepend the international prefix
        $msisdnValue = $msisdnFormat->getInternationalPrefix() . $stripped;
        $newMsisdn->setMsisdn($msisdnValue);
    }

}
