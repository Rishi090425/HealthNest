<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\DoctorCredentialsMail;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDoctorCredentials extends Command
{
    protected $signature = 'doctor:send-credentials {userId} {password}';
    protected $description = 'Send credentials email and WhatsApp to doctor in the background';

    public function handle()
    {
        $userId = $this->argument('userId');
        $password = $this->argument('password');

        $user = User::find($userId);
        if (!$user) {
            $this->error("User not found");
            return;
        }

        // 1. Send Credentials Email
        try {
            Mail::to($user->email)->send(
                new DoctorCredentialsMail($user->display_name, $user->email, $password)
            );
            Log::info("Background email sent successfully to: " . $user->email);
        } catch (\Exception $e) {
            Log::error('Background Email failed: ' . $e->getMessage());
        }

        // 2. Send Credentials via WhatsApp
        try {
            $loginUrl = url('/login');
            $docDisplayName = preg_match('/^(Dr\.?|Doctor)\s+/i', $user->name) ? $user->name : "Dr. " . $user->name;
            $msg = "Hello {$docDisplayName}, your professional account at Health Nest has been successfully created. You can log in using: Link: {$loginUrl} | Email: {$user->email} | Password: {$password}";
            WhatsappService::send($user->phone ?? '9999999999', $msg);
            Log::info("Background WhatsApp sent successfully to: " . $user->phone);
        } catch (\Exception $e) {
            Log::error('Background WhatsApp credentials failed: ' . $e->getMessage());
        }
    }
}
