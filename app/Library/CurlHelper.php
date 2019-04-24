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
    public static function init($url = null, $return_transfer = 1, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return_transfer);
        curl_setopt($ch, CURLOPT_HEADER, $headers);
        return $ch;
    }

    public static function close($ch)
    {
        curl_close($ch);
    }

    public static function exec($url, $return_transfer = 1, $headers = [], $json=true)
    {
        $ch = self::init($url, $return_transfer, $headers);
        $response = curl_exec($ch);
        if (curl_errno($ch))
            throw new \Exception("CURL error: " . curl_error($ch));
        self::close($ch);
        return $json ? json_decode($response, true) : $response;
    }
}
