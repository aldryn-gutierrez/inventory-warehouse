<?php 

namespace App\Repositories;

use App\Models\DistributionCenter;

class DistributionCenterRepository extends BaseRepository 
{
    /**
     * Specify Model name
     * 
     * @return string
     */
    public function getModelName()
    {
        return DistributionCenter::class;
    }
}