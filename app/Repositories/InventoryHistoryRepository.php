<?php 

namespace App\Repositories;

use App\Models\InventoryHistory;

class InventoryHistoryRepository extends BaseRepository 
{
    /**
     * Specify Model name
     * 
     * @return string
     */
    public function getModelName()
    {
        return InventoryHistory::class;
    }
}