<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStatus extends Model
{
	const SHIP_PRODUCT = 1;
	const REDUCE_PRODUCT = 2;
	const RESERVE_PRODUCT = 3;
	const ADD_PRODUCT = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventory_statuses';
}
