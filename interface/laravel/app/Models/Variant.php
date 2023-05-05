<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nhtsa_vehicle_id',
        'description',
        'has_safety_data',
        'picture_url',
        'overall_safety_rating',
        'overall_front_crash_rating',
        'front_crash_driver_side_rating',
        'front_crash_passenger_side_rating',
        'overall_side_crash_rating',
        'side_crash_driver_side_rating',
        'side_crash_passenger_side_rating',
        'overall_side_barrier_rating',
        'side_pole_crash_rating',
        'rollover_rating',
        'front_crash_picture_url',
        'side_crash_picture_url',
        'side_pole_crash_picture_url',
        'complaints_count',
        'complaints_url',
        'recalls_count',
        'recalls_url',
        'investigations_count',
        'investigations_url',
        'electronic_stability_control',
        'forward_collision_warning',
        'lane_departure_warning',
    ];
    /**
     * Get the model that owns the variant.
     */
    public function baseModel()
    {
        return $this->belongsTo(BaseModel::class);
    }

    //Function used to parse raw safety data from NHTSA
    public function storeSafetyData($safetyData) {
        $this->picture_url = $safetyData["VehiclePicture"];
		$this->overall_safety_rating = $safetyData["OverallRating"];
		$this->overall_front_crash_rating = $safetyData["OverallFrontCrashRating"];
		$this->front_crash_driver_side_rating = $safetyData["FrontCrashDriversideRating"];
		$this->front_crash_passenger_side_rating = $safetyData["FrontCrashPassengersideRating"];
		$this->overall_side_crash_rating = $safetyData["OverallSideCrashRating"];
		$this->side_crash_driver_side_rating = $safetyData["SideCrashDriversideRating"];
		$this->side_crash_passenger_side_rating = $safetyData["SideCrashPassengersideRating"];
		$this->overall_side_barrier_rating = $safetyData["sideBarrierRating-Overall"];
		$this->side_pole_crash_rating = $safetyData["SidePoleCrashRating"];
		$this->rollover_rating = $safetyData["RolloverRating"];
		$this->front_crash_picture_url = $safetyData["FrontCrashPicture"] ?? null;
		$this->side_crash_picture_url = $safetyData["SideCrashPicture"] ?? null;
		$this->side_pole_crash_picture_url = $safetyData["SidePolePicture"] ?? null;
		$this->complaints_count = $safetyData["ComplaintsCount"];
		$this->recalls_count = $safetyData["RecallsCount"];
		$this->investigations_count = $safetyData["InvestigationCount"];
		$this->electronic_stability_control = $safetyData["NHTSAElectronicStabilityControl"];
		$this->forward_collision_warning = $safetyData["NHTSAForwardCollisionWarning"];
		$this->lane_departure_warning = $safetyData["NHTSALaneDepartureWarning"];

        //Update the safety data flag denoting it has been fetched
        $this->has_safety_data = 1;

		$this->save();
    }

    //Function used to store all vehicle data in an object oriented manner
    public function getProfile() {
        $baseModel = $this->baseModel;
        info(print_r($baseModel->getSalesData(),1));
        $profile = array (
            "Description" => $this->description,
            "Picture" => $this->picture_url,
            "Ratings" => array (
                "Overall Safety Rating" => $this->overall_safety_rating,
                "Overall Front Crash Rating" => $this->overall_front_crash_rating,
                "Front Crash Driver Side Rating" => $this->front_crash_driver_side_rating,
                "Front Crash Passenger Side Rating" => $this->front_crash_passenger_side_rating,
                "Overall Side Crash Rating" => $this->overall_side_crash_rating,
                "Side Crash Driver Side Rating" => $this->side_crash_driver_side_rating,
                "Side Crash Passenger Side Rating" => $this->side_crash_passenger_side_rating,
                "Overall Side Barrier Rating" => $this->overall_side_barrier_rating,
                "Side Pole Crash Rating" => $this->side_pole_crash_rating,
                "Rollover Rating" => $this->rollover_rating
            ),
            "CrashTests" => array (
                "Front Crash Picture" => $this->front_crash_picture_url,
                "Side Crash Picture" => $this->side_crash_picture_url,
                "Side Pole Crash Picture" => $this->side_pole_crash_picture_url
            ),
            "NHTSAStats" => array (
                "Complaints" => $this->complaints_count,
                "Recalls" => $this->recalls_count,
                "Investigations" => $this->investigations_count,
            ),
            "SafetyFeatures" => array (
                "Electronic Stability Control" => $this->electronic_stability_control,
                "Forward Collision Warning" => $this->forward_collision_warning,
                "Lane Departure Warning" => $this->lane_departure_warning
            ),
            //Since sales data is not per variant, fetch it from the parent vehicle model
            "SalesData" => $this->baseModel->getSalesData()
        );

        return $profile;
    }
}
