<?php

namespace App\Core;

class SoapConfig
{
    public static function getWsdlPath(): string
    {
        $config = require \CONFIG_PATH . '/soap.php';
        return $config['wsdl_path'];
    }

    public static function getEndpoint(): string
    {
        $config = require \CONFIG_PATH . '/soap.php';
        return $config['endpoint'];
    }
}