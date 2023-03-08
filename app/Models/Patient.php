<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    public $table = 'Patient';
    public $primaryKey = 'idPatient';
    public $timestamps = false;
    protected $fillable = [
        'idPerson',
        'assignmentStatus',
    ];
}
