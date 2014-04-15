<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\Entity;

use Matmar10\Bundle\MsisdnFormatBundle\Entity\MsisdnFormatInterface;

interface MsisdnInterface
{

    public function setMsisdn($msisdn);

    public function getMsisdn();

    public function __toString();

    public function asMobile();

    public function setMsisdnFormat(MsisdnFormatInterface $msisdnFormat);

    public function getMsisdnFormat();

}
