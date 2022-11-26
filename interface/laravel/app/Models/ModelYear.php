<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelYear extends Model
{
    use HasFactory;

    protected $fillable = ['year'];

    /**
     * Get the makes for the model year.
     */
    public function makes()
    {
        return $this->hasMany(Make::class);
    }
}