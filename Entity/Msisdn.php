<?php

namespace Lmh\Bundle\MsisdnBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Lmh\Bundle\MsisdnBundle\Entity\MsisdnFormat;

class Msisdn
{

    /**
     * @Assert\NotBlank()
     */
    protected $msisdn;

    /**
     * @Type("\Msisdn\Entity\MsisdnFormat")
     */
    protected $msisdnFormat;

    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
    }

    public function getMsisdn()
    {
        return $this->msisdn;
    }

    public function __toString()
    {
        return $this->msisdn;
    }

    public function asMobile()
    {
        return $this->msisdnFormat->getNationalDialingPrefix() .
            substr($this->msisdn, strlen($this->msisdnFormat->getInternationalPrefix()));
    }

    public function setMsisdnFormat(MsisdnFormat $msisdnFormat)
    {
        $this->msisdnFormat = $msisdnFormat;
    }

    public function getMsisdnFormat()
    {
        return $this->msisdnFormat;
    }
}
