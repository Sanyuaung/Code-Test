<?php

namespace App\Services\InternetServiceProvider;

use App\Services\InternetServiceProvider\InternetServiceProviderInterface;

class OoredooServiceProvider implements InternetServiceProviderInterface
{
    private $month;

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }
    public function getMonth(): int 
    {
        return $this->month;
    }

    public function calculateTotalAmount(): float
    {
        return $this->month * 60.00; // Example calculation
    }
}