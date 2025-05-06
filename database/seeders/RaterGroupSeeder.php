<?php

namespace Database\Seeders;

use App\Models\RaterGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RaterGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        RaterGroup::create(['name' => 'Managers']);
        RaterGroup::create(['name' => 'Peers']);
        RaterGroup::create(['name' => 'Subordinates']);
        RaterGroup::create(['name' => 'Friends and Family']);
    }
}
