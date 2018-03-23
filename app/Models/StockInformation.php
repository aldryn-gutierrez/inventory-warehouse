<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInformation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_informations';

    public function calculateRemainingQuantityByInventoryStatusId(int $inventoryStatusId)
    {
        $remainingQuantity = $this->remaining_quantity;
        switch ($inventoryStatusId) {
            case InventoryStatus::SHIP_PRODUCT:
            case InventoryStatus::REDUCE_PRODUCT:
            case InventoryStatus::RESERVE_PRODUCT:
                return $remainingQuantity -= 1;
                break;
            
            case InventoryStatus::ADD_PRODUCT :
                return $remainingQuantity += 1;
                break;
        }

        return $remainingQuantity;
    }
}
