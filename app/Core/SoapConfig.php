<?php
namespace GinpacSoap\Core;

final class SoapConfig
{
    public const NS = 'http://saludtotal.local/pacientes';

    public static function wsdlPath(): string
    {
        return realpath(__DIR__ . '/../../public/wsdl/pacientes.wsdl');
    }

    public static function storageXml(): string
    {
        return realpath(__DIR__ . '/../../storage/pacientes.xml');
    }
}
