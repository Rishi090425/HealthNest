<?php

namespace App\Services;

use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send a simulated WhatsApp message and log it.
     */
    public static function send($to, $message)
    {
        // In a real production app, you would use Twilio or Gupshup API here:
        // $twilio->messages->create("whatsapp:$to", ["from" => "whatsapp:...", "body" => $message]);

        // For Health Nest Demo: Simulate and Log
        Log::info("WHATSAPP SENT TO $to: $message");

        return WhatsappLog::create([
            'phone_number' => $to,
            'message'      => $message,
            'status'       => 'sent',
            'message_id'   => 'simulated-' . uniqid()
        ]);
    }

    public static function sendAppointmentReminder($appointment)
    {
        $name = $appointment->patient->user->name;
        $doctor = $appointment->doctor->user->name;
        $time = $appointment->appointment_time;
        $date = $appointment->appointment_date->format('d M Y');
        
        $msg = "Hello $name, this is a reminder from Health Nest for your appointment with Dr. $doctor on $date at $time. Please be on time!";
        
        return self::send($appointment->patient->user->phone ?? '9999999999', $msg);
    }
}
