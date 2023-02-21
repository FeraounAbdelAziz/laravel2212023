<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    public $table = 'Patient';
    protected $fillable = [
        'idPerson',
        'assignmentStatus',
    ];
    // public function Person()
    // {
    //     return $this->belongsTo(Person::class, 'idPerson');
    // }
}
