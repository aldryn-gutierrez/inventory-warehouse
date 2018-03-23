<?php 

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\InventoryStatus;

class InventoryRepository extends BaseRepository 
{
    /**
     * Specify Model name
     * 
     * @return string
     */
    public function getModelName()
    {
        return Inventory::class;
    }

    public function getInventorySummary()
    {
    	$inventories = $this->all();
    	
        $inventoryReport = [];
        foreach ($inventories as $inventory) {
            $product = $inventory->product;
            $distributionCenter = $inventory->distributionCenter;
            $remainingQuantity = $inventory->stockInformation->remaining_quantity;
            $pendingShipped = $inventory->invetoryHistories->where('inventory_status_id', InventoryStatus::SHIP_PRODUCT)->count();

            $inventoryReport[] = [
                'product_name' => $product->name,
                'distribution_center' => $distributionCenter->name,
                'remaining_quantity' => $remainingQuantity,
                'pending_shipped' => $pendingShipped,
            ];
        }

        return $inventoryReport;
    }
}