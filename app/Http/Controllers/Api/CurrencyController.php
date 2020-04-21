<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Currency as CurrencyResource;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    /**
     * @param CurrencyService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function conversation(CurrencyService $service)
    {
        return response()->json([
            'rates' => CurrencyResource::collection(
                $service->getAll()->keyBy('code')
            ),
            'baseCurrency' => $service->getDefaultCurrencyCode(),
        ]);
    }
}
