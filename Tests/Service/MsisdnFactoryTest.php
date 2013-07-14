<?php

namespace Lmh\Bundle\MsisdnBundle\Tests\Service;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MsisdnFactoryTest extends WebTestCase
{

    protected static $factory;

    public function setUp()
    {
        self::createClient();
        self::$factory = self::$kernel->getContainer()->get('lmh_msisdn.msisdn_factory');
    }

    /**
     * @dataProvider provideTestGetData
     */
    public function testGet($expectException, $country, $mobileNumber, $isMsisdn = false)
    {
        if($expectException) {
            $this->setExpectedException('Lmh\Bundle\MsisdnBundle\Exception\InvalidFormatException');
        }

        $msisdn = self::$factory->get($country, $mobileNumber, $isMsisdn);

        $this->assertInternalType('string', $msisdn->getMsisdn());
        $this->assertInstanceOf('Lmh\Bundle\MsisdnBundle\Entity\Msisdn', $msisdn);
        $this->assertInstanceOf('Lmh\Bundle\MsisdnBundle\Entity\MsisdnFormat', $msisdn->getMsisdnFormat());
        $this->assertEquals($msisdn->getMsisdn(), $msisdn->__toString());
        $this->assertEquals($msisdn->getMsisdn(), (string)$msisdn);
        if($isMsisdn) {
            $this->assertEquals($mobileNumber, $msisdn->getMsisdn());
        } else {
            $this->assertEquals($mobileNumber, $msisdn->asMobile());
        }
    }

    public function provideTestGetData()
    {
        $enabledCountries = array('AT', 'DE', 'ES', 'FR', 'GB', 'LU', 'NL', 'PT');

        $fixturesFilename = __DIR__.'/../fixtures/test-msisdns-valid.csv';

        $fileHandle = fopen($fixturesFilename, 'r');
        if(!$fileHandle) {
            throw new RuntimeException("Could not open fixtures filename '$fixturesFilename'.");
        }

        // skip the first line
        if(!fgetcsv($fileHandle, 1000, ",")) {
            throw new RuntimeException("Couldn't read '$fixturesFilename' as CSV data.");
        }

        $fixtures = array();
        while(false !== ($csvLineData = fgetcsv($fileHandle, 1000, ','))) {

            if(false === array_search($csvLineData[1], $enabledCountries)) {
                continue;
            }

            print_r($csvLineData);

            $fixtures[] = array(
                false,
                $csvLineData[1],
                $csvLineData[2],
                true,
            );
        }
        return $fixtures;

        return array(
            array(
                false,
                'US',
                '4156014888',
            ),
            array(
                false,
                'US',
                '4156014888',
                false,
            ),
            array(
                true,
                'US',
                '4156014888',
                true,
            ),
        );
    }
}
