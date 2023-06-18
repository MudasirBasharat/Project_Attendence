<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class total_record extends Model
{
    use HasFactory;

    protected $fillable= [
        'remote_hours',
        'physical_hours',
        'total_duration',
        'Attendence'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
