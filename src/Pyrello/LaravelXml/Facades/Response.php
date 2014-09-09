<?php namespace Pyrello\LaravelXml\Facades;

use Illuminate\Support\Facades\Response as IlluminateResponse;
use Illuminate\Support\Contracts\ArrayableInterface;
use Pyrello\LaravelXml\XmlResponse;

class Response extends IlluminateResponse {

    /**
     * Return a new XML response from the application.
     *
     * @param  string|array  $data
     * @param  int    $status
     * @param  array  $headers
     * @return \Pyrello\XmlResponse\XmlResponse
     */
    public static function xml($data = array(), $status = 200, array $headers = array())
    {
        if ($data instanceof ArrayableInterface)
        {
            $data = $data->toArray();
        }

        return new XmlResponse($data, $status, $headers);
    }

}
