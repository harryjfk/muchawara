<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 30/12/18
 * Time: 0:03
 */

namespace App\Http\Controllers\Api;


class RestClient
{
    public static function CallAPI($method, $url, $user, $pass, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":

                if ($data) {
                    curl_setopt($curl, CURLOPT_POST, count($data));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                } else
                    curl_setopt($curl, CURLOPT_POST, 1);


                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

//        $headers = array('Content-Type: application/json',/*"Authorization: ".DefaultController::getAuthorization($user,$pass)*/);
        $headers = array();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        $r = $result;
        // echo $r;
        curl_close($curl);

        return $r;
    }

}