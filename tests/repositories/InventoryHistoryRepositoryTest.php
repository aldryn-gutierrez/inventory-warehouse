<?php

use App\Models\InventoryHistory;
use App\Repositories\InventoryHistoryRepository;

class InventoryHistoryRepositoryTest extends TestCase
{
    public function testGetModelName()
    {
        $repository = new InventoryHistoryRepository();
        $this->assertEquals(InventoryHistory::class, $repository->getModelName());
    }
}
