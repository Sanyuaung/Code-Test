<?php

namespace Tests\Unit;

use App\Services\InternetServiceProvider\OoredooServiceProvider;
use Tests\TestCase;

class OoredooServiceTest extends TestCase
{
    public function test_calculate_total_amount()
    {
        $service = new OoredooServiceProvider();
        $service->setMonth(1); // Example month

        $totalAmount = $service->calculateTotalAmount();

        $this->assertIsNumeric($totalAmount);
        // Add more specific assertions based on your business logic
    }

    public function test_set_month()
    {
        $service = new OoredooServiceProvider();
        $service->setMonth(2);

        $this->assertEquals(2, $service->getMonth());
    }
}