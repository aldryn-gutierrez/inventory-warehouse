<?php

use App\Models\StockInformation;
use App\Repositories\StockInformationRepository;

class StockInformationRepositoryTest extends TestCase
{
    public function testGetModelName()
    {
        $repository = new StockInformationRepository();
        $this->assertEquals(StockInformation::class, $repository->getModelName());
    }
}
