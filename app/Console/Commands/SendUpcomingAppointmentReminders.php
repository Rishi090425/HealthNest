<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Setting;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendUpcomingAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send WhatsApp reminders to both patients and doctors 4 to 5 hours before their upcoming approved appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Fetch approved appointments for the next 2 days to check their precise hours
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'approved')
            ->where('whatsapp_reminder_sent', false)
            ->whereBetween('appointment_date', [
                Carbon::now()->toDateString(), 
                Carbon::now()->addDays(2)->toDateString()
            ])
            ->get();

        $this->info("Checking " . $appointments->count() . " approved appointments...");
        $sentCount = 0;

        foreach ($appointments as $appt) {
            try {
                // Combine appointment date and time to get a precise Carbon instance
                $dateTimeStr = $appt->appointment_date->format('Y-m-d') . ' ' . $appt->appointment_time;
                $apptDateTime = Carbon::parse($dateTimeStr);

                // Calculate precise difference in hours
                $diffInHours = $now->diffInHours($apptDateTime, false); // false gives positive for future

                // Check if appointment is coming up in the 4 to 5 hour window (up to 5 hours)
                if ($diffInHours > 0 && $diffInHours <= 5) {
                    $patientName = $appt->patient->user->name;
                    $doctorName = $appt->doctor->user->name;
                    $patientPhone = $appt->patient->user->phone;
                    $doctorPhone = $appt->doctor->user->phone;
                    $time = $appt->appointment_time;
                    $approxHours = round($diffInHours);

                    $docDisplayName = preg_match('/^(Dr\.?|Doctor)\s+/i', $doctorName) ? $doctorName : "Dr. " . $doctorName;

                    $this->info("Sending 4-5h reminder for Appointment ID: {$appt->id} ({$docDisplayName} & Patient {$patientName} at {$time})");

                    // 1. Send to Patient
                    if ($patientPhone) {
                        $patientMsg = "Hello {$patientName}, this is a reminder from Health Nest that your appointment with {$docDisplayName} is coming up in about {$approxHours} hours at {$time} today. Please be on time!";
                        WhatsappService::send($patientPhone, $patientMsg);
                    }

                    // 2. Send to Doctor
                    if ($doctorPhone) {
                        $doctorMsg = "Hello {$docDisplayName}, this is a reminder from Health Nest that you have an upcoming appointment with patient {$patientName} scheduled in about {$approxHours} hours at {$time} today.";
                        WhatsappService::send($doctorPhone, $doctorMsg);
                    }
                    
                    // Mark as sent
                    $appt->whatsapp_reminder_sent = true;
                    $appt->save();
                    $sentCount++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to automatically send upcoming reminder WhatsApp for appointment ID {$appt->id}: " . $e->getMessage());
                $this->error("Error for Appointment ID {$appt->id}: " . $e->getMessage());
            }
        }

        $this->info("Done. Sent $sentCount upcoming appointment reminders to both doctors and patients.");
        return Command::SUCCESS;
    }
}
