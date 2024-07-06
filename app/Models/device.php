<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class device extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        'name',
        'ip',
        'location',
        'tempC',
        'humi',
        'dsm_consentrate',
        'dsm_particle',
        'air_quality_label',
        'sensor_value',
    ];

}
