<?php

namespace App\Models;


use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens, Notifiable;
    public $table = 'Doctor';
    protected $primaryKey = 'idDoctor'; // this is for the tokenable_id error  we set the primary then the token will be generate to the user !
    public $timestamps = false; // because we don't need it

    protected $fillable = [
        'email',
        'password',
        'idPerson',
        'isVerified',
    ];
    public function person()
    {
        return $this->belongsTo(Person::class, 'idPerson');
    }
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'idDoctor' => $this->idDoctor,
            'exp' => Carbon::now()->addHours(24)->timestamp,
        ];
    }

}
