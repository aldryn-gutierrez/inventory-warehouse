<?php

use App\Models\DistributionCenter;
use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\Product;
use App\Repositories\InventoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InventoryRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetModelName()
    {
        $repository = new InventoryRepository();
        $this->assertEquals(Inventory::class, $repository->getModelName());
    }

    public function testGetInventorySummary()
    {
        $singaporeDistributionCenter = DistributionCenter::find(1);
        $thailandDistributionCenter = DistributionCenter::find(2);

        $firstProduct = Product::find(1);
        $stockInformationForSingapore = $this->createStockInformation(35, 30);
        $stockInformationForThailand = $this->createStockInformation(25, 5);

        $inventoryInSingapore = $this->createInventory(
            $firstProduct->id,
            $singaporeDistributionCenter->id,
            $stockInformationForSingapore->id
        );

        $inventoryInThailand = $this->createInventory(
            $firstProduct->id,
            $thailandDistributionCenter->id,
            $stockInformationForThailand->id
        );

        $this->createInventoryHistory($inventoryInThailand->id, InventoryStatus::SHIP_PRODUCT);
        $this->createInventoryHistory($inventoryInThailand->id, InventoryStatus::SHIP_PRODUCT);

        $expectedResult = [
            [
                'product_name' => $firstProduct->name,
                'distribution_center' => $singaporeDistributionCenter->name,
                'remaining_quantity' => $stockInformationForSingapore->remaining_quantity,
                'pending_shipped' => 0,
            ],
            [
                'product_name' => $firstProduct->name,
                'distribution_center' => $thailandDistributionCenter->name,
                'remaining_quantity' => $stockInformationForThailand->remaining_quantity,
                'pending_shipped' => 2,
            ]
        ];

        $repository = new InventoryRepository();
        $this->assertEquals($expectedResult, $repository->getInventorySummary());
    }
}
