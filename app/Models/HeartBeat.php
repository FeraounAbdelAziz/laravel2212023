<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeartBeat extends Model
{
    use HasFactory;
    public $table = 'HeartBeat';
    protected $primaryKey = 'idHeartBeat';
    protected $fillable = ['idPatient',];
}
