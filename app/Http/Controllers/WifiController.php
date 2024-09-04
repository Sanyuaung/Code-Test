<?php

namespace App\Http\Controllers;

use App\Calculators\WifiCalculator;
use App\Services\MptServiceProvider;
use App\Services\OoredooServiceProvider;
use Illuminate\Http\Request;

class WifiController extends Controller
{
    public function getMptInvoiceAmount(Request $request)
    {
        $month = $request->input('month', 1);
        $calculator = new WifiCalculator(new MptServiceProvider());

        return response()->json([
            'data' => $calculator->calculate($month),
        ]);
    }

    public function getOoredooInvoiceAmount(Request $request)
    {
        $month = $request->input('month', 1);
        $calculator = new WifiCalculator(new OoredooServiceProvider());

        return response()->json([
            'data' => $calculator->calculate($month),
        ]);
    }
}