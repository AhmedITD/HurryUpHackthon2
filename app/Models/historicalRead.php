<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historicalRead extends Model
{
    use HasFactory;
    protected $fillable = [
        'device_id',
        'tempC',
        'humi',
        'dsm_consentrate',
        'dsm_particle',
        'air_quality_label',
        'sensor_value',
    ];

}
