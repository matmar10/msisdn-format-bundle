<?php

namespace Lmh\Bundle\MsisdnBundle\Service;

use Lmh\Bundle\MsisdnBundle\Entity\MsisdnFormat;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Yaml\Parser;
use XMLReader;

class MsisdnFormatConfigurationService
{

    public static $msisdnFormatConfigFilename;

    public static $msisdnFormatData;

    protected static $xml;

    protected static $countryFormats = array();

    public function __construct($msisdnFormatConfigFilename)
    {
        self::$msisdnFormatConfigFilename = $msisdnFormatConfigFilename;
        self::$xml = new XMLReader();
        self::$xml->open(self::$msisdnFormatConfigFilename);
    }

    public function getCountries($countries)
    {
        $this->setCountryData($countries);
        return array_intersect_key(self::$countryFormats, array_flip($countries));
    }

    public function get($country, $property = false)
    {
        if(!array_key_exists($country, self::$countryFormats)) {
            $this->setCountryData($country);
        }
        
        $msisdnFormat = self::$countryFormats[$country];

        if($property) {
            $method = 'get' . ucwords($property);
            return $msisdnFormat->$method();
        }
        
        return $msisdnFormat;
    }

    public function setCountryData($targetCountryOrCountries = null)
    {
        $xml = self::$xml;

        while($xml->read()) {

            $country = null;

            if('country' !== $xml->name) {
                continue;
            }

            $xml->moveToAttribute('code');
            $country = $xml->value;

            // check this is the target country, if requested
            if(!is_null($targetCountryOrCountries)) {
                if(is_array($targetCountryOrCountries)) {
                    if(false == array_search($country, $targetCountryOrCountries)) {
                        continue;
                    }
                } elseif($country !== $targetCountryOrCountries) {
                    continue;
                }
            }

            self::$countryFormats[$country] = $this->buildMsisdnFormat($xml, $country);

            // stop if only seaching for one and only one
            if(!is_null($targetCountryOrCountries) && !is_array($targetCountryOrCountries)) {
                break;
            }
        }
    }

    protected function buildMsisdnFormat(XmlReader $xml, $country)
    {

            $formats = array();
            $prefix = null;
            $nationalDialingPrefix = null;
            $exampleMobile = null;

            // save the prefix
            if($xml->moveToAttribute('prefix')) {
                $prefix = $xml->value;
            }

            // save the national dialing prefix
            if($xml->moveToAttribute('nationalDialingPrefix')) {
                $nationalDialingPrefix = $xml->value;
            }

            // save the example mobile number
            if($xml->moveToAttribute('exampleMobile')) {
                $exampleMobile = $xml->value;
            }

            // read everything, stopping at the format entities
            while($xml->read()) {

                // we found a format entity
                if(XMLReader::ELEMENT === $xml->nodeType && 'format' === $xml->name) {
                    // grab the regular expression
                    $xml->moveToAttribute('expression');
                    $formats[] = '/^' . $xml->value . '$/';
                    continue;
                }

                // break to outer loop if we've advanced beyond the current country
                if('country' === $xml->name) {
                    break;
                }
            }

            $msisdnFormat = new MsisdnFormat();
            $msisdnFormat->setCountry($country);
            $msisdnFormat->setInternationalPrefix($prefix);
            $msisdnFormat->setNationalDialingPrefix($nationalDialingPrefix);
            $msisdnFormat->setExampleMobile($exampleMobile);
            $msisdnFormat->setFormats($formats);
            return $msisdnFormat;
    }
    
    public function __destruct() {
        self::$xml->close();
    }
}
