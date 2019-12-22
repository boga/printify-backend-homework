<?php

namespace App\Helpers;

use \Exception;

class IP {
    static function getIP(): ?string {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }

        return null;
    }

    static function getCountry(?string $ip = null): ?string {
        $ip = $ip ?? self::getIP();
        if (empty($ip)) {
            return null;
        }
        try {
//            $ipDataStr = file_get_contents(sprintf("https://ipapi.co/%s/json", $ip));
            $apiKey = config("services.ipstack.key");
            $ipDataStr = file_get_contents("http://api.ipstack.com/${ip}?access_key=${apiKey}&format=1");
        } catch (Exception $e) {
            return null;
        }

        if (empty($ipDataStr)) {
            return null;
        }
        $ipData = json_decode($ipDataStr, true);

        return $ipData["country_code"] ?? null;
    }


}

