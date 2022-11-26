<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ModelYear;
use App\Models\Make;
use App\Models\BaseModel;
use App\Models\Variant;
use App\Http\Controllers\Scrapy;

/*
	Controller used to access the external NHTSA database found at NHTSA.gov
	Since we are accessing a public database we want to be polite, so we are storing the data fetched
	This handler determines whether we need new data or have the data already to reduce requests and increase politeness
	When getting new data for a given variant we're checking if we have sales data and calling a web scraper controller
	The Scrapy controller can be found at /interface/laravel/app/Http/Controllers/Scrapy.php
*/
class NHTSA extends Controller
{
	//Function used to get all years available
	public static function getModelYears() {
		//See if we've already got the years
		$modelYear = ModelYear::first();
		if (is_null($modelYear)) {
			//If we don't have any years, request the years from NHTSA
			$response = Http::withHeaders([
				'Accept' => 'application/json'
			])->get(config('services.nhtsa.url') . "/SafetyRatings");
			//For each year we need to create a new eloquent model
			//We do this by creating an array of data representing the eloquent models and insert it
			$now = Carbon::now('utc')->toDateTimeString();
			$years = [];
			foreach ($response['Results'] as $row) {
				$years[] = array (
					'year' => $row['ModelYear'],
					'created_at'=> $now,
					'updated_at'=> $now
				);
			}
			ModelYear::insert($years);
		}
		$modelYear = ModelYear::select('id', 'year')
			->orderBy('year', 'desc')
			->get();

		return $modelYear;
	}
    
	//Function used to get all makes available for a year
	//Takes a ModelYear ID as a parameter
	public static function getMakes($id) {
		//Find the ModelYear
		$modelYear = ModelYear::find($id);
		//Check if it already has associated makes
		if ($modelYear->makes->count() === 0) {
			//If we don't have any makes, request the makes from NHTSA
			$response = Http::withHeaders([
				'Accept' => 'application/json'
			])->get(config('services.nhtsa.url') . "/SafetyRatings" 
				. "/modelyear" . "/$modelYear->year"
			);
			//For each model we need to create a new eloquent model
			//We do this by creating an array of data representing the eloquent models and insert it
			$now = Carbon::now('utc')->toDateTimeString();
			$makes = [];
			foreach ($response['Results'] as $row) {
				$makes[] = array (
					'name' => $row['Make'],
					'model_year_id' => $modelYear->id,
					'created_at'=> $now,
					'updated_at'=> $now
				);
			}
			Make::insert($makes);
			$modelYear->refresh();
		}

		return $modelYear->makes->sortBy('name')->values()->all();
	}
    
	//Function used to get all models available for a make
	public static function getModels($id, $year) {
		//Check the make for associated models
		$make = Make::find($id);
		if ($make->baseModels->count() === 0) {
			//If we don't have any models, request them from NHTSA
			$response = Http::withHeaders([
				'Accept' => 'application/json'
			])->get(config('services.nhtsa.url') . "/SafetyRatings" 
				. "/modelyear/$year"
				. "/make/$make->name"
			);
			//For each model we need to create a new eloquent model
			//We do this by creating an array of data representing the eloquent models and insert it
			$now = Carbon::now('utc')->toDateTimeString();
			$models = [];
			foreach ($response['Results'] as $row) {
				$models[] = array (
					'name' => $row['Model'],
					'make_id' => $make->id,
					'created_at'=> $now,
					'updated_at'=> $now
				);
			}
			BaseModel::insert($models);
			$make->refresh();
		}

		return $make->baseModels->sortBy('name')->values()->all();
	}
    
	//Function used to get all variants for a model
	public static function getVariants($id, $year, $make) {
		//Check the model for associated variants
		$model = BaseModel::find($id);
		if ($model->variants->count() === 0) {
			//If we don't have any variants, we call Scrapy to get sales data
			if ($model->has_sales_data === 0) {
				$salesData = Scrapy::scrapeCarSales($make, $model->name, $year);
				info(print_r($salesData,1));
				$model->storeSalesData($salesData);
			}
			//If we don't have any variants, we get them from NHTSA
			$response = Http::withHeaders([
				'Accept' => 'application/json'
			])->get(config('services.nhtsa.url') . "/SafetyRatings" 
				. "/modelyear/$year"
				. "/make/$make"
				. "/model/$model->name"
			);
			//NHTSA doesn't return a variant name so we'll need a delimiter to split it out of the vehicle description
			$delim = $model->name . " ";
			//For each model we need to create a new eloquent model
			//We do this by creating an array of data representing the eloquent models and insert it
			$now = Carbon::now('utc')->toDateTimeString();
			$variants = [];
			foreach ($response['Results'] as $row) {
				$variants[] = array (
					'nhtsa_vehicle_id' => $row['VehicleId'],
					'name' => explode($delim, strtoupper($row['VehicleDescription']))[1],
					'base_model_id' => $model->id,
					'description' => $row['VehicleDescription'],
					'created_at'=> $now,
					'updated_at'=> $now
				);
			}
			Variant::insert($variants);
			$model->refresh();
		}

		return $model->variants->sortBy('name')->values()->all();	
	}
    
	//Function used to get all data for a vehicle profile
	public static function getVehicleProfile($id) {
		//Fetch the variant eloquent model
		$variant = Variant::find($id);
		//Check if we have fetched safety data, if not we get it from NHTSA
		if ($variant->has_safety_data === 0) {
			$response = Http::withHeaders([
				'Accept' => 'application/json'
			])->get(config('services.nhtsa.url') . "/SafetyRatings/VehicleId/"
				. "$variant->nhtsa_vehicle_id"
			);
			//Pass the raw data to the variant model to store it
			$variant->storeSafetyData($response['Results'][0]);
		}
		//Fetch all the data from the variant model
		//Since scraped data is not filtered by the NHTSA variant, the eloquent variant model will fetch it from the parent vehicle model
		return $variant->getProfile();
	}
}