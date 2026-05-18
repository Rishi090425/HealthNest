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
    protected $description = 'Automatically send WhatsApp reminders to patients for upcoming approved appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) Setting::get('appointment_reminder_hours', 24);
        $maxDateTime = Carbon::now()->addHours($hours);

        // Fetch approved appointments for the next 2 days to ensure we cover all potential reminder windows
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'approved')
            ->where('whatsapp_reminder_sent', false)
            ->whereBetween('appointment_date', [
                Carbon::now()->toDateString(), 
                Carbon::now()->addDays(2)->toDateString()
            ])
            ->get();

        $this->info("Checking " . $appointments->count() . " approved appointments for the next 2 days...");
        $sentCount = 0;

        foreach ($appointments as $appt) {
            try {
                // Combine appointment date and time to get a precise Carbon instance
                $dateTimeStr = $appt->appointment_date->format('Y-m-d') . ' ' . $appt->appointment_time;
                $apptDateTime = Carbon::parse($dateTimeStr);

                // Check if appointment is in the future and falls within the reminder window
                if ($apptDateTime->isAfter(Carbon::now()) && $apptDateTime->isBefore($maxDateTime)) {
                    $this->info("Sending reminder to patient for Appointment ID: {$appt->id} (Dr. {$appt->doctor->user->name} on {$appt->appointment_date->format('Y-m-d')} at {$appt->appointment_time})");
                    
                    WhatsappService::sendAppointmentReminder($appt);
                    
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

        $this->info("Done. Sent $sentCount upcoming appointment reminders.");
        return Command::SUCCESS;
    }
}
