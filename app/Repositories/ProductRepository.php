<?php 

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository 
{
    /**
     * Specify Model name
     * 
     * @return string
     */
    public function getModelName()
    {
        return Product::class;
    }
}