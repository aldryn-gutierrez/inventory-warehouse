<?php

use Illuminate\Database\Seeder;
use App\Models\DistributionCenter;

class DistributionCenterTableSeeder extends Seeder
{
    public function run()
    {
        $this->createDistributionCenter('Singapore');
        $this->createDistributionCenter('Thailand');
    }

    protected function createDistributionCenter($countryName) 
    {
        $distributionCenter = new DistributionCenter();
        $distributionCenter->name = $countryName;
        $distributionCenter->save();
    }
}
