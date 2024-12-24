<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendMessage(string $to, string $message)
    {
        if(config('sms.status')){

            $response = Http::withHeaders([
                'accept' => 'application/json ',
            ])->post(config('sms.api_url'), [
                'recipient' => '880' . $to,
                'sender_id' => config('sms.sender_id'),
                'type' => 'plain',
                'message' => $message,
            ]);
            // Log::debug('SMS Payload:' , [
            //     'recipient' => '880' . $to,
            //     'sender_id' => $setting->sender_id,
            //     'type' => 'plain',
            //     'message' => $message,
            // ]);

            if($response->successful()){
                Log::debug('SMS response is ok:' . $response->body());
                return [
                    'status' => 'success',
                    'message' => $response->body(),
                ];
            }else{
                Log::error('Error occurred while sending sms: ' . $response->body());
            }
        }else{
            Log::info('The SMS api status is turned off.');
        }
    }
}
