<?php

namespace App\Calculators;

use App\Services\InternetServiceProvider\InternetServiceProviderInterface;

class WifiCalculator
{
    private $provider;

    public function __construct(InternetServiceProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function calculate(int $month): float
    {
        $this->provider->setMonth($month);
        return $this->provider->calculateTotalAmount();
    }
}