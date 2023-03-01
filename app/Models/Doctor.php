<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,HasApiTokens,Notifiable;
    public $table = 'Doctor';
    protected $primaryKey = 'idDoctor'; // this is for the tokenable_id error  we set the primary then the token will be generate to the user !
    public $timestamps = false; // because we don't need it
    protected $fillable = [
        'idPerson',
        'password',
        'isVerified',
    ];
    public function person()
    {
        return $this->belongsTo(Person::class, 'idPerson');
    }

}
