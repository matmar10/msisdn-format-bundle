msisdn-format-bundle
=============

Build Status:

- Master: [![Build Status](http://ci.asedik.com/buildStatus/icon?job=msisdn-format-bundle_master)](http://ci.asedik.com/job/msisdn-format-bundle_master/)
- Develop: [![Build Status](http://ci.asedik.com/buildStatus/icon?job=msisdn-format-bundle_develop)](http://ci.asedik.com/job/msisdn-format-bundle_develop/)

A lightweight library to validate a msisdn (international representation of a mobile phone) which makes uses of Symfony2 validator

Updates
==
2. Open the country formats file located at: `vendor/lmh/bitcoin-by-mobile/src/Msisdn/Resources/config/msisdn-country-formats.xml`
3. Find the country code for the country you are launching
4. Verify the following attributes exist: 
	* country prefix `prefix="XX"`
    * example mobile `exampleMobile="XX XX XX XX XX"`
    * national dialing prefix `nationalDialingPrefix="0"`
5. Modify the unit test to expect to be able to validate the msisdn by modifying `Tests/Fixtures/target-countries.yml`
6. Run the unit test: `./vendor/bin/phpunit`
7. Verify that the unit test passed; if not, check the format data including the regular expressions. This will require some googling. The test relies on known valid msisdns 
8. Once the test is passing, commit and push your changes. Tag a new release version
9. Update the bitcoinbymobile project dependency version to correspond to your tagged release verison of the msisdn-format-bundle