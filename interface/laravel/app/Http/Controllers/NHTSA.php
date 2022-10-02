<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NHTSA extends Controller
{
    private $response;

	//Function used to get all years available
	public static function getModelYears() {
		$response = Http::withHeaders([
			'Accept' => 'application/json'
		])->get(config('services.nhtsa.url') . "/SafetyRatings");
		
		return $response['Results'];
	}
    
	//Function used to get all makes available for a year
	public static function getMakes($year) {
		$response = Http::withHeaders([
			'Accept' => 'application/json'
		])->get(config('services.nhtsa.url') . "/SafetyRatings/modelyear/" . "$year");
		
		return $response['Results'];
	}
    
	//Function used to get all models available for a year
	public static function getModels($year, $make) {
		$response = Http::withHeaders([
			'Accept' => 'application/json'
		])->get(config('services.nhtsa.url') . "/SafetyRatings/modelyear/" . "$year" . "/make" . "/$make");
		
		return $response['Results'];
	}
    
	//Function used to get all variants for a year
	public static function getVariants($year, $make, $model) {
		$response = Http::withHeaders([
			'Accept' => 'application/json'
		])->get(config('services.nhtsa.url') . "/SafetyRatings/modelyear/" . "$year" . "/make" . "/$make" . "/model" . "/$model");
		
		return $response['Results'];
	}
    
	//Function used to get safety data for a year
	public static function getSafety($id) {
		$response = Http::withHeaders([
			'Accept' => 'application/json'
		])->get(config('services.nhtsa.url') . "/SafetyRatings/VehicleId/" . "$id");
		
		return $response['Results'];
	}
}