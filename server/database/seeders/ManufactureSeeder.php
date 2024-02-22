<?php

namespace Database\Seeders;

use App\Models\Manufacture;
use Illuminate\Database\Seeder;

class ManufactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manufacture::factory(100)
            ->hasImage(1)
            ->create();
    }
}
