<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Entity;

use Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnFormatInterface;
use Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Msisdn implements MsisdnInterface
{

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    protected $msisdn;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
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

    public function setMsisdnFormat(MsisdnFormatInterface $msisdnFormat)
    {
        $this->msisdnFormat = $msisdnFormat;
    }

    public function getMsisdnFormat()
    {
        return $this->msisdnFormat;
    }
}
