<?php

namespace App\Repositories;

use Validator;
use App\Models\Settings;
use Hash;
use App\Repositories\CreditRepository;
use App\Repositories\Admin\UtilityRepository;
// use App\Repositories\ProfileRepository;
use \Exception;

class VerotelRepository
{

	const SIGNATURE_KEY = 'ewch5T6JY42S3QpqxeZXydYjGp6yUQ'; // Set this to your signature key
    // The signature key can be found in the Verotel control center (

    const PURCHASE_URL = 'https://secure.verotel.com/startorder';
    const STATUS_URL   = 'https://secure.verotel.com/status/purchase';
    const VERSION      = '3';

	private $creditRepo;
	
	public function __construct()
	{
		$this->creditRepo = app("App\Repositories\CreditRepository");
	}

	/**
     * FUNCTION get_purchase_URL:
     * -----------------------------
     * - IN $signatureKey - STRING
     * - IN $params - ASOC_ARRAY
     * - RETURNS STRING - purchase_URL
     */
    public static function get_purchase_URL($signatureKey, $params) {
        if (!isset($signatureKey) || !is_string($signatureKey) || empty($signatureKey)) {
            throw new Exception("no signatureKey given");
        }

        if (!isset($params) || empty($params)) {
            throw new Exception("no params given");
        }

        if (!is_array($params)) {
            throw new Exception("invalid params");
        }

        return self::generate_URL(self::PURCHASE_URL, $signatureKey, $params);
    }
    /**
     * Returns url
     * - IN $baseURL - STRING (url : http://www.xyz.com)
     * - IN $signatureKey  - STRING
     * - IN $params  - ASOC_ARRAY (URL params)
     */
    private static function generate_URL($baseURL, $signatureKey, $params) {
        $params['version'] = self::VERSION;

        ksort($params, SORT_REGULAR);
        $outArray = array();
        foreach ($params as $key => $value) {
            if($value !== "") {
                $outArray[$key] = $value;
            }
        }

        $signature = self::get_signature($signatureKey, $outArray);
        $outArray['signature'] = $signature;

        return self::build_URL($baseURL, $outArray);
    }

    public static function get_signature($signatureKey, $params) {
        $filtered = self::filter_params($params);
        // dd($filtered);
        return self::signature($signatureKey, $filtered);
    }

    /**
     * Returns filtered parameters assoc-array
     * - IN $params - ASOC_ARRAY (unfiltered URL params)
     */
    private static function filter_params($params) {

        $keys = array_keys($params);
        $filtered = array();
        $regexp = '/^(
            version
            | shopID
            | price(Amount|Currency)
            | description
            | referenceID
            | saleID
            | type
            | custom\w*
            )$/x';

        foreach ($keys as $key) {
            if (preg_match($regexp, $key)) {
                $filtered[$key] = $params[$key];
            }
        }

        return $filtered;
    }

     ######## PRIVATE ############################

    /**
     * Common furnction for generating signature
     * - IN $signatureKey - STRING
     * - IN $params - ASOC_ARRAY
     */
    private static function signature($signatureKey, $params) {
        $outArray = array($signatureKey);
        ksort($params, SORT_REGULAR);
        foreach ($params as $key => $value) {
            array_push($outArray, "$key=$value");
        }

        return strtolower(sha1(join(":", $outArray)));
    }

    /**
     * Returns URL string
     * - IN $baseURL - STRING (url : http://www.xyz.com)
     * - IN $params  - ASOC_ARRAY - (URL params)
     */
    private static function build_URL($baseURL, $params) {
        $arr = array();

        foreach ($params as $key => $value) {
            $arr[] = "$key=" . urlencode($value);
        }
        return $baseURL . "?" . join("&", $arr);
    }

}