<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PaymentValidationService
{
    protected const CACHE_KEY = 'payment-limits';
    protected const CACHE_TTL = 120;

    /**
     * Where the overall validation process executes
     * @param array $data
     * @return JsonResponse
     */
    public function validate(array $data): JsonResponse
    {
        $paymentLimitCollection = collect($this->fetchPaymentLimits());
        $specificLimit = $paymentLimitCollection->where('id', $data['fiat'])->first();
        
        if (!$specificLimit || !isset($specificLimit['paymentLimits'])) {
            return Response::error(['message' => 'Does not adhere to requirements.']);
        }
        $limitCollection = collect($specificLimit['paymentLimits']);

        $limitCollection->each(function ($limit, $index) use ($data) {
            if ($data['amount'] >= $limit['min'] && $data['amount'] <= $limit['max']) {
                if ($data['payment'] == $limit['id']) {
                    return Response::success();
                }
            }
        });

        return Response::error(['message' => 'Does not adhere to requirements.']);
    }
    
    /**
     * Calls API endpoint for payment limits
     * @return mixed
     */
    public function fetchPaymentLimits(): mixed
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $endpoint = config('services.payment.url');
            $apiKey = config('services.payment.key');
            $apiSecret = config('services.payment.secret');
            $response = Http::withHeaders([
                $apiKey => $apiSecret
            ])->get(
                $endpoint
            );

            if ($response->clientError()) {
                // todo
            }

            if ($response->serverError()) {
                // todo
                // return response()->json([

                // ], 422);
            }

            return response()->json();
        });
    }
}