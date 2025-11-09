<?php
namespace GinpacSoap\Core;

use GinpacSoap\Services\PatientService;
use SoapServer;

class SoapServerHandler
{
    public static function publish(): void
    {
        $wsdl = SoapConfig::wsdlPath();
        $options = [
            'uri' => SoapConfig::NS,
            'cache_wsdl' => WSDL_CACHE_DISK,
            'trace' => 1,
            'exceptions' => 1,
        ];
        $server = new SoapServer($wsdl, $options);
        $server->setClass(PatientService::class);
        $server->handle();
    }
}
