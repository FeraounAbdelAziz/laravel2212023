<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gps extends Model
{
    use HasFactory;
    public $table = 'Gps';
    protected $primaryKey = 'idGps';
    public $timestamps = false;
    protected $fillable = ['idPatient',];
}
