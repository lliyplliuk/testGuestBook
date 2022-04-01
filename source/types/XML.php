<?php


namespace types;

use SimpleXMLElement;
use types\interfaces\ConnectorInterface;

class XML implements ConnectorInterface
{
    private string $fileName;

    public function __construct(string $fileName = '')
    {
        if (empty($fileName))
            $this->fileName = __DIR__ . '/../XML/XML.xml';
        else
            $this->fileName = $fileName;
    }


    public function load(): array
    {
        $xml = simplexml_load_file($this->fileName);
        $ret = [];
        if (isset($xml->message)) {
            foreach ($xml->message as $message) {
                $msg = new Msg();
                $msg->load((array)$message);
                $ret[] = $msg;
            }
        }
        return $ret;
    }

    public function save(array $arr): void
    {
        $xml = new SimpleXMLElement('<messages/>');
        $this->array_to_xml($arr, $xml);
        $file = fopen($this->fileName, 'w');
        fwrite($file, $xml->asXML());
        fclose($file);
    }

    /** Генерируем XML из массива
     * @param $array
     * @param $xml
     */
    private function array_to_xml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subNode = $xml->addChild("$key");
                    $this->array_to_xml($value, $subNode);
                } else {
                    $subNode = $xml->addChild('message');
                    $this->array_to_xml($value, $subNode);
                }
            } else {
                if (!is_numeric($key)) {
                    $xml->addChild("$key", "$value");
                } else {
                    $xml->addChild('message', "$value");
                }
            }
        }
    }


}