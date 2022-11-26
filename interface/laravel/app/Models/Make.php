<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the model year that owns the make.
     */
    public function modelYear()
    {
        return $this->belongsTo(ModelYear::class);
    }

    /**
     * Get the models for the make.
     */
    public function baseModels()
    {
        return $this->hasMany(BaseModel::class);
    }
}
