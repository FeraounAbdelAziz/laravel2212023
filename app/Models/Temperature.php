<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    use HasFactory;
    public $table = 'Temperature';
    protected $primaryKey = 'idTemperature';
    public $timestamps = false;
    protected $fillable = ['idPatient','tempValue'];
}
