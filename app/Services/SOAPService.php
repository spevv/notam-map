<?php

namespace App\Services;

use App\Services\Interfaces\ConnectionInterface;

/**
 * Class SOAPService
 * @package App\Services
 */
class SOAPService implements ConnectionInterface
{
    /**
     * @var array options
     */
    protected $options = [
        'uri' => 'http://schemas.xmlsoap.org/soap/envelope/',
        'style' => SOAP_RPC,
        'use' => SOAP_ENCODED,
        'soap_version' => SOAP_1_1,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'connection_timeout' => 15,
        'trace' => true,
        'encoding' => 'UTF-8',
        'exceptions' => true,
    ];

    /**
     * SOAPService constructor.
     */
    public function __construct()
    {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 900);
        ini_set('default_socket_timeout', 15);
    }

    /**
     * @param string $url
     * @param string $method
     * @param mixed $parameters
     *
     * @return mixed
     */
    public function send(string $url, string $method, $parameters = '')
    {
        try {
            $data = (new \SoapClient($url, $this->options))->$method($parameters);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return simplexml_load_string($data);
    }
}
