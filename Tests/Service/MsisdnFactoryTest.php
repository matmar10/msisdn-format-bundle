<?php

namespace Lmh\Bundle\MsisdnBundle\Tests\Service;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MsisdnFactoryTest extends WebTestCase
{

    protected static $factory = null;

    public function setUp()
    {
        if(is_null(self::$factory)) {
            self::createClient();
            self::$factory = self::$kernel->getContainer()->get('lmh_msisdn.msisdn_factory');
        }
    }

    /**
     * @dataProvider provideTestGetData
     */
    public function testGet($expectException, $country, $mobileNumber, $isMsisdn = false)
    {
        if(false !== $expectException) {
            $this->setExpectedException($expectException);
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
        $enabledCountries = array(
            'AT', 'BE', 'BG', 'CH', 'DE', 'EE', 'ES', 'FR', 'GB', 'IT', 'LU', 'NL', 'PT', 'SE'
        );

        $fixturesFilename = __DIR__.'/../Fixtures/test-msisdns-valid.csv';

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
                $fixtures[] = array(
                    'Lmh\Bundle\MsisdnBundle\Exception\MissingFormatConfigurationException',
                    $csvLineData[1],
                    $csvLineData[2],
                    true,
                );
                continue;
            }

            $fixtures[] = array(
                false,
                $csvLineData[1],
                $csvLineData[2],
                true,
            );
        }

        fclose($fileHandle);
        return $fixtures;
    }
}
