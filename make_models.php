<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    'EmergencyAlert',
    'WellnessLog',
    'NutritionLog',
    'Dependent',
    'SymptomCheck',
    'Certificate',
    'NoteTemplate',
    'ChatbotSession',
    'Room',
    'BedAssignment',
    'Medication',
    'InventoryLevel',
    'DispensingRecord',
    'Shift',
    'LeaveRequest',
    'Equipment',
    'Announcement',
    'WhatsappLog',
    'SurveyResponse',
    'WebauthnKey',
    'DoctorPreference',
    'UserPreference'
];

foreach ($models as $model) {
    echo "Creating $model...\n";
    Illuminate\Support\Facades\Artisan::call('make:model', ['name' => $model, '-m' => true]);
    echo Illuminate\Support\Facades\Artisan::output();
}
