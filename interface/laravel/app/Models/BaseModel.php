<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'has_sales_data'
    ];

    /**
     * Get the make that owns the make.
     */
    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    /**
     * Get the variants for the model.
     */
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function storeSalesData($salesData) {
        $numArray = array_map('intval', $salesData);
        $this->autotrader_avg_price = '$' . number_format(array_sum($numArray)/count($numArray), 2);
        $this->autotrader_records_searched = count($numArray);
        
        /*$this->autotempest_for_sale = $salesData
        $this->carvana_for_sale = $salesData
        $this->carscom_for_sale = $salesData
        $this->truecar_for_sale = $salesData
        $this->ebay_for_sale = $salesData
        $this->carsoup_for_sale = $salesData
        $this->carsandbids_for_sale = $salesData
        $this->other_for_sale = $salesData*/

        $this->has_sales_data = 1;

        $this->save();
    }

    public function getSalesData() {
        return array (
            'AutoTrader Avg Price' => $this->autotrader_avg_price,
            'AutoTrader Records Searched' => $this->autotrader_records_searched
        );
    }
}
