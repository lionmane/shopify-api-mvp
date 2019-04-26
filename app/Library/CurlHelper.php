<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/23/19
 * Time: 7:37 AM
 */

namespace App\Library;


class CurlHelper
{
    static $SUPPORTED_METHODS = ['GET', 'POST', 'PUT', 'DELETE'];

    public static function init($url = null, $method='GET', $data=[], $headers = [], $return_transfer = 1)
    {
        if (empty($method))
            $method = 'GET';
        $method = strtoupper($method);
        if (!in_array($method, self::$SUPPORTED_METHODS))
            throw new \Exception("Invalid HTTP method: $method");

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return_transfer);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (in_array($method, ['POST', 'PUT'])) {
            if (!empty($data))
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        return $ch;
    }

    public static function close($ch)
    {
        curl_close($ch);
    }

    public static function exec($url, $method='GET', $data=[], $headers = [], $return_transfer = 1, $json=true)
    {
        $ch = self::init($url, $method, $data, $headers, $return_transfer);
        $response = curl_exec($ch);
        if (curl_errno($ch))
            throw new \Exception("CURL error: " . curl_error($ch));
        self::close($ch);
        return $json ? json_decode($response, true) : $response;
    }
}
