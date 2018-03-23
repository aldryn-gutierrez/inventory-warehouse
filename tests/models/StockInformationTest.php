<?php

use App\Models\InventoryStatus;
use App\Models\StockInformation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockInformationTest extends TestCase
{
    use DatabaseTransactions;

    protected function createStockInformation($initialQuantity, $remainingQuantity)
    {
        $stockInformation = new StockInformation();
        $stockInformation->initial_quantity = $initialQuantity;
        $stockInformation->remaining_quantity = $remainingQuantity;
        $stockInformation->save();

        return $stockInformation;
    }

    public function calculateRemainingQuantityByInventoryStatusIdProvider()
    {
        return [
            'ship-product' => [InventoryStatus::SHIP_PRODUCT, 30, 10, 9],
            'reduce-product' => [InventoryStatus::REDUCE_PRODUCT, 30, 10, 9],
            'reserve-product' => [InventoryStatus::RESERVE_PRODUCT, 30, 10, 9],
            'add-product' => [InventoryStatus::ADD_PRODUCT, 30, 10, 11],
        ];
    }

    /**
     * @dataProvider calculateRemainingQuantityByInventoryStatusIdProvider
     **/
    public function testCalculateRemainingQuantityByInventoryStatusId(
        $inventoryStatusId,
        $initialQuantity,
        $remainingQuantity,
        $expectedResponse
    ) {
        $stockInformation = $this->createStockInformation($initialQuantity, $remainingQuantity);
        $remainingQuantity = $stockInformation->calculateRemainingQuantityByInventoryStatusId($inventoryStatusId);

        $this->assertEquals($expectedResponse, $remainingQuantity);
    }
}
