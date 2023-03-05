<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    public $table = 'Person';
    protected $primaryKey = 'idPerson';
        public $timestamps = false;
    protected $fillable = ['firstName', 'lastName', 'birthdate','telNum', 'adress'];
}
