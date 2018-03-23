<?php

use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\StockInformation;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function createInventory($productId, $distributionCenterId, $stockInformationId)
    {
        $inventory = new Inventory();
        $inventory->product_id = $productId;
        $inventory->distribution_center_id = $distributionCenterId;
        $inventory->stock_information_id = $stockInformationId;
        $inventory->save();

        return $inventory;
    }

    protected function createStockInformation($initialQuantity, $remainingQuantity)
    {
        $stockInformation = new StockInformation();
        $stockInformation->initial_quantity = $initialQuantity;
        $stockInformation->remaining_quantity = $remainingQuantity;
        $stockInformation->save();

        return $stockInformation;
    }

    protected function createInventoryHistory($inventoryId, $inventoryStatusId)
    {
        $inventoryHistory = new InventoryHistory();
        $inventoryHistory->inventory_id = $inventoryId;
        $inventoryHistory->inventory_status_id = $inventoryStatusId;
        $inventoryHistory->save();

        return $inventoryHistory;
    }

    public function getJsonResponse()
    {
        return json_decode($this->response->getContent(), true);
    }
}
