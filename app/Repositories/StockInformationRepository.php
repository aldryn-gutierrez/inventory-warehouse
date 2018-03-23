<?php 

namespace App\Repositories;

use App\Models\StockInformation;

class StockInformationRepository extends BaseRepository 
{
    /**
     * Specify Model name
     * 
     * @return string
     */
    public function getModelName()
    {
        return StockInformation::class;
    }
}