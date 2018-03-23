<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        $this->createProduct('Lip Gloss');
        $this->createProduct('Eyeliner');
    }

    protected function createProduct($name) 
    {
        $product = new Product();
        $product->name = $name;
        $product->save();
    }
}
