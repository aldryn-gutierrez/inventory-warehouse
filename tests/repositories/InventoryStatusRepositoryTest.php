<?php

use App\Models\InventoryStatus;
use App\Repositories\InventoryStatusRepository;

class InventoryStatusRepositoryTest extends TestCase
{
    public function testGetModelName()
    {
        $repository = new InventoryStatusRepository();
        $this->assertEquals(InventoryStatus::class, $repository->getModelName());
    }
}
