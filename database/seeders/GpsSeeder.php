<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gps;

class GpsSeeder extends Seeder
{
    public function run()
    {
        Gps::factory()->count(50)->create();
    }
}
