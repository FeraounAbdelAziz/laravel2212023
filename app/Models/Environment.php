<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    use HasFactory;
    public $table = 'Environment';
    protected $primaryKey = 'idEnvironment';
    protected $fillable = ['idPatient',];

}
