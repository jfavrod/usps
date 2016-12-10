<?php
namespace Epoque\USPS;


class Address
{
    public $street;
    public $city;
    public $state;
    public $zip;


    /**
     * @param String $xml An XML tree containing an address
     * from the USPS API.
     */

    public function __construct($xml)
    {
        if ($xml) {
            $xml = new \SimpleXMLElement($xml);

            // Adding ."" to the end converts the SimpleXMLElement
            // in to a string.
            $this->street = $xml->Address->Address2."";
            $this->city = $xml->Address->City."";
            $this->state = $xml->Address->State."";
            $this->zip = $xml->Address->Zip5.'-'.$xml->Address->Zip4;
        }
    }
}

