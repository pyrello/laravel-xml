<?php namespace Pyrello\LaravelXml;

use SimpleXMLElement;
use stdClass;

class XmlTools
{
    public static function serialize($data, $rootElement = 'items', $xmlVersion = '1.0', $xmlEncoding = 'UTF-8')
    {
        $xml = new SimpleXMLElement('<?xml version="' . $xmlVersion .'" encoding="' . $xmlEncoding . '"?><response/>');
        static::encode($data, $xml);

        return $xml->asXML();
    }

    public static function encode($arr, SimpleXMLElement $xml = null)
    {
        foreach ($arr as $key => $item) {
            if (is_array($item)) {

                // If the $key is numeric, we convert it to the singular form
                // of the element name it is contained in
                if (is_numeric($key)) {
                    $key = str_singular($xml->getName());
                }
                static::encode($item, $xml->addChild($key));

            } else {

                // If the item is a boolean, convert it to a string, so that it shows up
                if (is_bool($item)) {
                    $item = ($item) ? 'true' : 'false';
                }

                // We use the $xml->{$key} form to add the item because this causes
                // conversion of '&' => '&amp;'
                $xml->{$key} = $item;
            }
        }

        return $xml->asXML();
    }

    /**
     * @param SimpleXMLElement $xml
     * @return array
     */
    public static function decode(SimpleXMLElement $xml)
    {
        // todo: add option to use stdClass instead of array.
        $arr = [];

        foreach ($xml as $element)
        {
            $e = get_object_vars($element);
            if (!empty($e))
            {
                $value = $element instanceof SimpleXMLElement ? static::decode($element) : $e;
            }
            else
            {
                $value = trim($element);
            }

            $parent = current($element->xpath('..'));
            $tag = $element->getName();
            if (str_singular($parent->getName()) === $tag) {
                $tag = null;
            }

            if ($tag) {
                $arr[$tag] = $value;
            }
            else {
                $arr[] = $value;
            }
        }

        return $arr;
    }
} 