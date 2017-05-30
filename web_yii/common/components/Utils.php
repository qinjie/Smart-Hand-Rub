<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 23/01/2017
 * Time: 12:39 PM
 */

namespace common\components;


use Yii;

class Utils
{
    public static function getAddressFromGPS($latitude, $longitude)
    {
        $key_api = Yii::$app->params['google_geocoding_key'];
        $request_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng="
            . $latitude . "," . $longitude . "&key=" . $key_api;

        $data = @file_get_contents($request_url);
        $jsondata = json_decode($data, true);
        $d = $jsondata['results'][0]['address_components'];
        $result = "";
        $first = true;
        foreach ($d as $item) {
            if (!$first) {
                $result .= ", ";
            }

            $first = FALSE;
            //echo( $city->name );
            $result .= $item['long_name'];// . ",";
        }
//        $res = implode(",",$result);
        return ($result);
    }

}