<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

interface MsisdnFormatInterface
{

    public function setCountry($country);

    public function getCountry();

    public function addFormat($format);

    public function setFormats(array $formats);

    public function getFormats();

    public function setInternationalPrefix($internationalPrefix);

    public function getInternationalPrefix();

    public function setNationalDialingPrefix($nationalDialingPrefix);

    public function getNationalDialingPrefix();

    public function setExampleMobile($exampleMobile);

    public function getExampleMobile();
}
