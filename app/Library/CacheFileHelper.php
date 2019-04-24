<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/23/19
 * Time: 7:54 AM
 */

namespace App\Library;


class CacheFileHelper
{
    public static function load($filename, $cache_age_threshold = 600, $json=true)
    {
        $path = base_path($filename);
        if (file_exists($path)) {
            $modify_time = filemtime($path);
            $age = time() - $modify_time;
            if (!$cache_age_threshold || $age < abs($cache_age_threshold)) {
                error_log("*** Returning cached file $filename (cache is $age seconds old) ***");
                $contents = file_get_contents($path);
                if ($contents == '[]')
                    return false;
                return $json ? json_decode($contents, true) : $contents;
            }
        }

        return false;
    }

    public static function save($filename, $data, $json=true)
    {
        $path = base_path($filename);
        if ($json && !is_string($data))
            $data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($path, $data);
    }
}
