<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    public $table = 'Assignment';
    public $timestamps = false;

    protected $fillable = [
        'idPatient',
        'idDevice',
        'returnDate',
    ];
}
