<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\SmsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class SmsServiceTest extends TestCase
{
    public function test_sms_service_successful_response()
    {
        $smsService = new SmsService();

        $response = $smsService->sendMessage('01611996667', 'Test message');

        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('message', $response);
    }

    public function test_sms_service_disabled_status()
    {
        Config::set('sms.status', false);

        $smsService = new SmsService();

        $response = $smsService->sendMessage('1234567890', 'Test message');

        $this->assertNull($response); // SMS service should not send messages if disabled
    }
}
