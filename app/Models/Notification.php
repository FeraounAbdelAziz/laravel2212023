<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $table = 'notification';
    protected $primaryKey = 'idNotification';
    public $timestamps = false;

    protected $fillable = ['content','type', 'dateCreate'];
}
