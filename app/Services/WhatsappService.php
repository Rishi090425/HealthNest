<?php

namespace App\Services;

use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    /**
     * Send a real or simulated WhatsApp message.
     */
    public static function send($to, $message)
    {
        // Clean phone number (remove spaces, dashes, ensure country code)
        $cleanPhone = preg_replace('/[^0-9+]/', '', $to);
        
        // Add default Indian country code +91 if not present and is 10 digits
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '+91' . $cleanPhone;
        }

        $sentSuccessfully = false;
        $messageId = 'simulated-' . uniqid();
        $providerUsed = 'simulated';

        // 1. TRY ULTRAMSG (Easiest personal WhatsApp QR-code linking)
        $ultraMsgInstance = env('ULTRAMSG_INSTANCE_ID');
        $ultraMsgToken = env('ULTRAMSG_TOKEN');
        if ($ultraMsgInstance && $ultraMsgToken) {
            try {
                $response = Http::post("https://api.ultramsg.com/{$ultraMsgInstance}/messages/chat", [
                    'token' => $ultraMsgToken,
                    'to'    => $cleanPhone,
                    'body'  => $message
                ]);

                if ($response->successful() && isset($response['sent']) && $response['sent'] == 'true') {
                    $sentSuccessfully = true;
                    $messageId = $response['id'] ?? 'ultra-' . uniqid();
                    $providerUsed = 'ultramsg';
                    Log::info("REAL WHATSAPP SENT via UltraMsg to {$cleanPhone}");
                } else {
                    Log::error("UltraMsg Failed: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("UltraMsg Exception: " . $e->getMessage());
            }
        }

        // 2. TRY TWILIO (Industry Standard)
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioFrom = env('TWILIO_WHATSAPP_FROM'); // e.g. whatsapp:+14155238886
        if (!$sentSuccessfully && $twilioSid && $twilioToken && $twilioFrom) {
            try {
                // Format destination for Twilio
                $twilioTo = str_starts_with($cleanPhone, 'whatsapp:') ? $cleanPhone : "whatsapp:{$cleanPhone}";
                
                $response = Http::withBasicAuth($twilioSid, $twilioToken)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$twilioSid}/Messages.json", [
                        'To'   => $twilioTo,
                        'From' => $twilioFrom,
                        'Body' => $message
                    ]);

                if ($response->successful()) {
                    $sentSuccessfully = true;
                    $messageId = $response['sid'] ?? 'twilio-' . uniqid();
                    $providerUsed = 'twilio';
                    Log::info("REAL WHATSAPP SENT via Twilio to {$cleanPhone}");
                } else {
                    Log::error("Twilio Failed: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Twilio Exception: " . $e->getMessage());
            }
        }

        // 3. FALLBACK TO SIMULATOR LOG (If no credentials or APIs fail)
        if (!$sentSuccessfully) {
            Log::info("WHATSAPP SIMULATED SEND TO {$cleanPhone}: {$message}");
        }

        // Log transaction in database
        return WhatsappLog::create([
            'phone_number' => $cleanPhone,
            'message'      => $message,
            'status'       => $sentSuccessfully ? 'sent' : 'simulated',
            'message_id'   => $messageId
        ]);
    }

    public static function sendAppointmentReminder($appointment)
    {
        $name = $appointment->patient->user->name;
        $doctor = $appointment->doctor->user->name;
        $time = $appointment->appointment_time;
        $date = $appointment->appointment_date->format('d M Y');
        
        $docName = preg_match('/^(Dr\.?|Doctor)\s+/i', $doctor) ? $doctor : "Dr. " . $doctor;
        $msg = "Hello $name, this is a reminder from Health Nest for your appointment with $docName on $date at $time. Please be on time!";
        
        return self::send($appointment->patient->user->phone ?? '9999999999', $msg);
    }
}
