<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventories';

    public function stockInformation()
    {
        return $this->hasOne('App\Models\StockInformation', 'id', 'stock_information_id');
    }

    public function invetoryHistories()
    {
        return $this->hasMany('App\Models\InventoryHistory', 'inventory_id', 'id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function distributionCenter()
    {
        return $this->hasOne('App\Models\DistributionCenter', 'id', 'distribution_center_id');
    }
}
