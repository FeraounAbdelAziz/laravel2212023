<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    public $table = 'Device';
    protected $primaryKey = 'idDevice'; // this is for the tokenable_id error  we set the primary then the token will be generate to the user !
    public $timestamps = false; // because we don't need it

    protected $fillable = [
        'idDevice',
    ];

}
