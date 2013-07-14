<?php

$filename = dirname(__FILE__).'/msisdn-country-formats.xml';

if(!file_exists($filename)) {
    die("ERROR: cannot open msisdn country formats file: " . $filename);
}

$xml = new XMLReader();
if(!$xml->open($filename)) {
    die("ERROR: cannot parse msisdn country formats file as XML: " . $filename);
}

if(false === array_key_exists(1, $argv)) {
    die("Usage:\t\tphp " . $argv[0] . " [country code] \nExample:\tphp " . $argv[0] . " GB\n");
}


$targetCountry = $argv[1];
$formats = array();
$prefix = null;
$nationalDialingPrefix = null;
$example = null;

while($xml->read()) {

    if('country' === $xml->name) {

        // check this is the target country
        $xml->moveToAttribute('code');
        if($xml->value === $targetCountry) {

            // save the prefix
            if($xml->moveToAttribute('prefix')) {
                $prefix = $xml->value;
            } else {
                $prefix = "NONE";
            }

            // save the national dialing prefix
            if($xml->moveToAttribute('nationalDialingPrefix')) {
                $nationalDialingPrefix = $xml->value;
            } else {
                $nationalDialingPrefix = "NONE";
            }
            
            // save the example mobile number
            if($xml->moveToAttribute('exampleMobile')) {
                $example = $xml->value;
            } else {
                $example = "NONE";
            }
            
            // read everything, stopping at the format entities
            while($xml->read()) {

                // we found a format entity
                if(XMLReader::ELEMENT === $xml->nodeType &&
                    'format' === $xml->name) {

                    // grab the regular expression
                    $xml->moveToAttribute('expression');
                    $formats[] = $xml->value;
                }

                // stop if we've advanced beyond the target country
                if('country' === $xml->name) {
                    break;
                }
            }
            break;
        }
    }
}

echo "Country: $targetCountry\n";
echo "====\n";
echo "Prefix is: $prefix\n";
echo "National dialing prefix is: $nationalDialingPrefix\n";
echo "Example is: $example\n";
echo "Formats are: \n";
foreach($formats as $format) {
    echo "\t$format\n";
}
echo "\n";

$xml->close();
