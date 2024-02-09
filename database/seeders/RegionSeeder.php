<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $regions = ['US', 'UE', 'AU', 'India', 'Online'];

        foreach($regions as $region) 
        {
            Region::create([
                'name' => $region,
            ]);
        }
        
    }
}
