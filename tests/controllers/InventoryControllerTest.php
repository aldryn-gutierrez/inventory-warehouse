<?php

use App\Models\DistributionCenter;
use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InventoryControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testReport()
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
                'pending_shipped' => 1,
            ]
        ];

        $this->get(
                '/api/inventory/report',
                []
            )
            ->assertResponseStatus(200);

        $response = $this->getJsonResponse();

        $this->assertEquals($expectedResult, $response);
    }

    public function testStoreValidationError()
    {
        $this->post(
                '/api/inventory',
                []
            )
            ->assertResponseStatus(400)
            ->seeJson([
                'errors' => 'The product id field is required., The distribution center '.
                    'id field is required., The quantity field is required.',
            ]);
    }

    public function testStoreInventoryAlreadyExists()
    {
        $distributionCenter = DistributionCenter::find(1);

        $product = Product::find(1);
        $stockInformation = $this->createStockInformation(35, 30);

        $this->createInventory(
            $product->id,
            $distributionCenter->id,
            $stockInformation->id
        );

        $this->post(
                '/api/inventory',
                [
                    'product_id' => $product->id,
                    'distribution_center_id' => $distributionCenter->id,
                    'quantity' => 300,
                ]
            )
            ->assertResponseStatus(400)
            ->seeJson([
                'errors' => 'Inventory with same product and distribution center exists',
            ]);
    }

    public function testStoreSuccess()
    {
        $distributionCenter = DistributionCenter::find(1);

        $product = Product::find(1);

        $this->post(
                '/api/inventory',
                [
                    'product_id' => $product->id,
                    'distribution_center_id' => $distributionCenter->id,
                    'quantity' => 300,
                ]
            )
            ->assertResponseStatus(200);

        $response = $this->getJsonResponse();

        $inventory = Inventory::find($response['id']);
        $this->assertNotNull($inventory);
        $this->assertEquals($product->id, $inventory->product_id);
        $this->assertEquals($distributionCenter->id, $inventory->distribution_center_id);

        $stockInformation = $inventory->stockInformation;
        $this->assertNotNull($stockInformation);
        $this->assertEquals(300, $stockInformation->remaining_quantity);
        $this->assertEquals(300, $stockInformation->initial_quantity);
    }

    public function testAdjustValidationError()
    {
        $this->post(
                '/api/inventory/adjust',
                []
            )
            ->assertResponseStatus(400)
            ->seeJson([
                'errors' => 'The inventory id field is required., The inventory status id field is required.',
            ]);
    }

    public function adjustProvider()
    {
        return [
            'ship-product' => [InventoryStatus::SHIP_PRODUCT, 30, 10, 9],
            'reduce-product' => [InventoryStatus::REDUCE_PRODUCT, 30, 10, 9],
            'reserve-product' => [InventoryStatus::RESERVE_PRODUCT, 30, 10, 9],
            'add-product' => [InventoryStatus::ADD_PRODUCT, 30, 10, 11],
        ];
    }

    /**
     * @dataProvider adjustProvider
     **/
    public function testAdjustSuccess($inventoryStatusId, $initialQuantity, $remainingQuantity, $expectedRemainingQuantity)
    {
        $distributionCenter = DistributionCenter::find(1);

        $product = Product::find(1);
        $stockInformation = $this->createStockInformation($initialQuantity, $remainingQuantity);

        $inventory = $this->createInventory(
            $product->id,
            $distributionCenter->id,
            $stockInformation->id
        );

        $this->post(
                '/api/inventory/adjust',
                [
                    'inventory_id' => $inventory->id,
                    'inventory_status_id' => $inventoryStatusId,
                ]
            )
            ->assertResponseStatus(204);

        $inventory = $inventory->fresh();
        $stockInformation = $inventory->stockInformation;

        $this->assertEquals($initialQuantity, $stockInformation->initial_quantity);
        $this->assertEquals($expectedRemainingQuantity, $stockInformation->remaining_quantity);
    }
}