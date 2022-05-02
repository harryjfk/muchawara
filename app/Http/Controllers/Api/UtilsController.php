<?php
/**
 * Created by PhpStorm.
 * User: DellK
 * Date: 24/07/2018
 * Time: 10:26
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Township;

class UtilsController extends Controller
{
  private $country;
  private $city;
  private $township;

  public function __construct(Country $country, City $city, Township $township)
  {
    $this->country = $country;
    $this->city = $city;
    $this->township = $township;
  }

    public function countries()
    {
        $country = $this->country->select('name')->get();

        return response()->json(
            [
                "status" => "success",
		"success_data" => [
                      "country" => $country
                ]
            ]
        );
    }

    public function cities()
    {

//        $cities = $this->city->select('name')->get();
//
//
        $countries = Country::all();

        $resp = array();
        foreach ($countries as $country)
        {
            $resp[] = array('name' => $country->name);

            foreach ($country->cities as $city) {
                $resp[] = array('name' => sprintf('%s, %s', $country->name, $city->name));

                foreach ($city->townships as $township) {
                    $resp[] = array('name' => sprintf('%s, %s, %s', $country->name, $city->name, $township->name));
                }
            }
        }

//        return $resp;
        return response()->json(
            [
                "status" => "success",
		"success_data" => [
                "city" => $resp
                ]
            ]
        );
    }

    public function townships() {

        $township = $this->township->select('name')->get();

        return response()->json(
            [
                "status" => "success",
		"success_data" => [
                "township" => $township
                 ]
            ]
        );
    }

    public function localities()
    {
       try {
           $country = $this->country->where('name', '=', 'Cuba')->first();

           $resp = array();

           $resp[] = array('id' => $country->name, 'text' => $country->name);

           foreach ($country->cities as $city) {
               $resp[] = array('name' => sprintf('%s, %s', $city->name, $country->name), 'text' => sprintf('%s, %s', $city->name, $country->name));

               foreach ($city->townships as $township) {
                   $resp[] = array('id' => sprintf('%s, %s, %s', $township->name, $city->name, $country->name), 'text' => sprintf('%s, %s, %s', $township->name, $city->name, $country->name));
               }
           }

           return response()->json([
               "status" => "success",
               "success_data" => [
                   "countriesCities" => $resp,
               ]
           ]);
       } catch (\Exception $e) {
           return response()->json(['mess' => $e->getMessage()]);
       }
    }

}