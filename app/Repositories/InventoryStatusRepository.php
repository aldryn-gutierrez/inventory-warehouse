<?php 

namespace App\Repositories;

use App\Models\InventoryStatus;

class InventoryStatusRepository extends BaseRepository 
{
    /**
     * Specify Model name
     * 
     * @return string
     */
    public function getModelName()
    {
        return InventoryStatus::class;
    }
}