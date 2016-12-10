<?php
namespace Epoque\USPS;
require 'Address.php';


/**
 * 
 */

class USPS
{
    const USPS_URL = 'http://production.shippingapis.com/ShippingAPI.dll';

    private static $defaults  = [
        'USERID' => ''
    ];

    private $config = [];
    

    /**
     * Populate $this->config at time of instantiation.
     *
     * @param assoc_array $spec Over-writes self::$defaults in
     * $this->config.
     */

    public function __construct($spec=[])
    {
        foreach (self::$defaults as $key => $val) {
            $this->config[$key] = $val;
        }

        if (is_array($spec) && !empty($spec)) {
            foreach ($spec as $key => $val) {
                if (array_key_exists($key, self::$defaults)) {
                    $this->config[$key] = $val;
                }
            }
        }
    }


    /**
     *
     * @param stdClass $address A generic object containing street,
     * city, state, and zip properties.
     */

    public function validateAddress($address)
    {
        $xml = '<AddressValidateRequest USERID="'.$this->config['USERID'].'">';
        $xml .= '<Address ID="0"><Address1></Address1>';
        $xml .= '<Address2>'.$address->street.'</Address2>';
        $xml .= '<City>'.$address->city.'</City>';
        $xml .= '<State>'.$address->state.'</State>';
        $xml .= '<Zip5>'.$address->zip.'</Zip5><Zip4></Zip4>';
        $xml .= '</Address></AddressValidateRequest>';

        $url  = self::USPS_URL . '?API=Verify';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POSTFIELDS, "XML=$xml");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close($curl);

        //return json_encode($data, TRUE);
        return new Address($data);
    }
}
