<?php

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    public function testGetModelName()
    {
        $repository = new ProductRepository();
        $this->assertEquals(Product::class, $repository->getModelName());
    }
}
