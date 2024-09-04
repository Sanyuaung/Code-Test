<?php

namespace Tests\Unit;

use App\Services\InternetServiceProvider\MptServiceProvider;
use Tests\TestCase;

class MptServiceTest extends TestCase
{
    public function test_calculate_total_amount()
    {
        $service = new MptServiceProvider();
        $service->setMonth(1);

        $totalAmount = $service->calculateTotalAmount();

        $this->assertEquals(50.00, $totalAmount);
    }
    public function test_set_month()
    {
        $service = new MptServiceProvider();
        $service->setMonth(2);

        $this->assertEquals(2, $service->getMonth());
    }
}