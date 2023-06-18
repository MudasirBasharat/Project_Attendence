<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class static_ip extends Model
{
    use HasFactory;
    protected $fillable=['ip_address','location'];
}
