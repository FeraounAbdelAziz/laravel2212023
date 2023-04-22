<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shock extends Model
{
    use HasFactory;
    public $table = 'Shock';
    protected $primaryKey = 'idShock';
    public $timestamps = false;

    protected $fillable = ['idPatient',];
}
