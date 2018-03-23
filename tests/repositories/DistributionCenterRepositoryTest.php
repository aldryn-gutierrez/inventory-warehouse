<?php

use App\Models\DistributionCenter;
use App\Repositories\DistributionCenterRepository;

class DistributionCenterRepositoryTest extends TestCase
{
    public function testGetModelName()
    {
        $repository = new DistributionCenterRepository();
        $this->assertEquals(DistributionCenter::class, $repository->getModelName());
    }
}
