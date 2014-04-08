<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Entity;

use Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnFormatInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MsisdnFormat implements MsisdnFormatInterface
{

    protected $country;

    protected $exampleMobile;

    protected $internationalPrefix;

    protected $nationalDialingPrefix;

    protected $formats;

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function addFormat($format)
    {
        $this->formats[] = $format;    
    }

    public function setFormats(array $formats)
    {
        $this->formats = $formats;
    }

    public function getFormats()
    {
        return $this->formats;
    }

    public function setInternationalPrefix($internationalPrefix)
    {
        $this->internationalPrefix = $internationalPrefix;
    }

    public function getInternationalPrefix()
    {
        return $this->internationalPrefix;
    }

    public function setNationalDialingPrefix($nationalDialingPrefix)
    {
        $this->nationalDialingPrefix = $nationalDialingPrefix;
    }

    public function getNationalDialingPrefix()
    {
        return $this->nationalDialingPrefix;
    }

    public function setExampleMobile($exampleMobile)
    {
        $this->exampleMobile = $exampleMobile;
    }

    public function getExampleMobile()
    {
        return $this->exampleMobile;
    }
}
