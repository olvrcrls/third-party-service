<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Services\PaymentValidationService;

class PaymentValidationController extends Controller
{  
    protected $service;

    public function __construct(PaymentValidationService $service)
    {
        $this->service = $service;
    }

    public function paymentValidations(PaymentRequest $request)
    {
        return $this->service->validate($request->validated());
    }
}
