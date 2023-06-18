<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class remote_record extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'workplace',
        'visit_time',
        'departure_time',
        'total_remote_duration',
    ];

}
