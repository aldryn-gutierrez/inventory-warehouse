<?php

use Illuminate\Database\Seeder;
use App\Models\InventoryStatus;

class InventoryStatusTableSeeder extends Seeder
{
    public function run()
    {
        $this->createInventoryStatus('Ship Item');
        $this->createInventoryStatus('Reduce Item');
        $this->createInventoryStatus('Reserve Item');
        $this->createInventoryStatus('Add Item');
    }

    protected function createInventoryStatus($name) 
    {
        $inventoryStatus = new InventoryStatus();
        $inventoryStatus->name = $name;
        $inventoryStatus->save();
    }
}
