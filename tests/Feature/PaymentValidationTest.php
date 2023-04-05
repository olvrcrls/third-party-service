<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentValidationTest extends TestCase
{
    protected $paymentLimitsEndpoint;
    protected $apiKey;
    protected $apiSecret;

    public function setUp(): void
    {
        parent::setUp();
        $this->paymentLimitsEndpoint = Config::get('services.payment.url');
        $this->apiKey = Config::get('services.payment.key');
        $this->apiSecret = Config::get('services.payment.secret');
    }

   /** @test */
    public function payment_validation_endpoint_accessible()
    {
        $this->get('/api/payment/validation')
            ->assertOk();
    }

    /** @test */
    public function payment_api_configuration_keys_are_set()
    {
        $this->assertNotEmpty($this->apiKey, "The API KEY is not set properly.");
        $this->assertNotEmpty($this->apiSecret, "The API SECRET is not set properly.");
    }
}
