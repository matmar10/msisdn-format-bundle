<?php

namespace Matmar10\Bundle\BitcoinAddressValidatorBundle\Tests;

use Matmar10\Bundle\BitcoinAddressValidatorBundle\Validator\Constraints\BitcoinAddress;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BitcoinAddressValidatorTest extends WebTestCase
{
    protected $validator = null;

    public function setUp()
    {
        if(is_null($this->validator)) {
            $client = self::createClient();
            $this->validator = $client->getContainer()->get('validator');
        }
    }

    public function provideFromFilename($filename)
    {
        $rawData = json_decode(file_get_contents($filename));
        $filteredTestAddresses = array();
        foreach($rawData as $keyData) {

            $bitcoinAddress = $keyData[0];
            $keyMeta = $keyData[2];

            if(false === property_exists($keyMeta, 'addrType')) {
                continue;
            }

            if('pubkey' !== $keyMeta->addrType) {
                continue;
            }

            $filteredTestAddresses[] = array($bitcoinAddress);
        }

        return $filteredTestAddresses;

    }

    public function provideTestValidateData()
    {
        return $this->provideFromFilename(__DIR__ . '/../Fixtures/base58_keys_valid.json');
    }

    /**
     * @dataProvider provideTestValidateData
     */
    public function testValidate($bitcoinAddress)
    {
        $errorList = $this->validator->validateValue(
            $bitcoinAddress,
            new BitcoinAddress()
        );
        $this->assertEquals(0, count($errorList), "Test valid public key '" . $bitcoinAddress . "' returns 0 errors.");
    }

    public function provideTestValidateInvalidData()
    {
        $filename = __DIR__ . '/../Fixtures/base58_keys_invalid.json';
        return json_decode(file_get_contents($filename));
    }

    /**
     * @dataProvider provideTestValidateInvalidData
     */
    public function testValidateInvalid($bitcoinAddress)
    {
        $errorList = $this->validator->validateValue(
            $bitcoinAddress,
            new BitcoinAddress()
        );
        $this->assertEquals(1, count($errorList), "Test valid public key '" . $bitcoinAddress . "' returns 0 errors.");
    }
}
