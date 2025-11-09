<?php
namespace GinpacSoap\Core;

class XmlManager
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
        if (!file_exists($this->file)) {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><pacientes/>');
            $xml->asXML($this->file);
        }
    }

    public function all(): \SimpleXMLElement
    {
        return simplexml_load_file($this->file);
    }

    public function save(\SimpleXMLElement $xml): bool
    {
        return $xml->asXML($this->file);
    }
}
