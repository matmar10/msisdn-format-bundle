<?php

namespace Lmh\Bundle\MsisdnBundle\Service;

use Lmh\Bundle\MsisdnBundle\Entity\MsisdnFormat;
use Lmh\Bundle\MsisdnBundle\Exception\MissingFormatConfigurationException;
use RuntimeException;
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
    }

    public function getCountries($countries)
    {
        $this->setCountryData($countries);
        foreach($countries as $country) {
            // assert that the format was found
            if(false === array_key_exists($country, self::$countryFormats)) {
                throw new MissingFormatConfigurationException("No msisdn configuration node found for the requested country '$country' in file '" . self::$msisdnFormatConfigFilename . "'");
            }
        }
        return array_intersect_key(self::$countryFormats, array_flip($countries));
    }

    public function get($country, $property = false)
    {
        if(!array_key_exists($country, self::$countryFormats)) {
            $this->setCountryData($country);
            // assert that the format was found
            if(false === array_key_exists($country, self::$countryFormats)) {
                throw new MissingFormatConfigurationException("No msisdn configuration node found for the requested country '$country' in file '" . self::$msisdnFormatConfigFilename . "'");
            }
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
        $xml->open(self::$msisdnFormatConfigFilename);

        $targets = is_array($targetCountryOrCountries) ?
            $targetCountryOrCountries : array( $targetCountryOrCountries );

        while($xml->read()) {

            $country = null;

            if('country' !== $xml->name) {
                continue;
            }

            $xml->moveToAttribute('code');
            $country = $xml->value;

            // check this is the target country, if requested
            if(!is_null($targets)) {
                if(false === array_search($country, $targets)) {
                    continue;
                }
            }

            self::$countryFormats[$country] = $this->buildMsisdnFormat($xml, $country);

            // stop if only seaching for one and only one
            if(!is_null($targetCountryOrCountries) && !is_array($targetCountryOrCountries)) {
                break;
            }
        }

        $xml->close();
    }

    protected function buildMsisdnFormat(XmlReader $xml, $country)
    {

        $formats = array();
        $prefix = null;
        $nationalDialingPrefix = null;
        $exampleMobile = null;
        $missingAttributeMessage = "No msisdn '%s' attribute found for country node '$country' in configuration file '" . self::$msisdnFormatConfigFilename . "'";

        // save the prefix
        if(!$xml->moveToAttribute('prefix')) {
            throw new MissingFormatConfigurationException(sprintf($missingAttributeMessage, 'prefix'));
        }
        $prefix = $xml->value;

        // save the national dialing prefix
        if(!$xml->moveToAttribute('nationalDialingPrefix')) {
            throw new MissingFormatConfigurationException(sprintf($missingAttributeMessage, 'nationalDialingPrefix'));
        }
        $nationalDialingPrefix = $xml->value;

        // save the example mobile number
        if(!$xml->moveToAttribute('exampleMobile')) {
            throw new MissingFormatConfigurationException(sprintf($missingAttributeMessage, 'exampleMobile'));
        }
        $exampleMobile = $xml->value;
        if('' === trim($exampleMobile)) {
            throw new MissingFormatConfigurationException("Attribute 'exampleMobile was blank for country ndoe '$country' in configuration file '" . self::$msisdnFormatConfigFilename . "'");
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

        if(!count($formats)) {
            throw new MissingFormatConfigurationException("No 'format' node(s) present for country node for '$country' within configuration file '" . self::$msisdnFormatConfigFilename . "'.");
        }

        $msisdnFormat = new MsisdnFormat();
        $msisdnFormat->setCountry($country);
        $msisdnFormat->setInternationalPrefix($prefix);
        $msisdnFormat->setNationalDialingPrefix($nationalDialingPrefix);
        $msisdnFormat->setExampleMobile($exampleMobile);
        $msisdnFormat->setFormats($formats);
        return $msisdnFormat;
    }
}
