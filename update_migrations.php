<?php

$migrationsDir = __DIR__ . '/database/migrations';
$files = scandir($migrationsDir);

$schemas = [
    'create_branches_table.php' => "\$table->id();\n            \$table->string('name');\n            \$table->string('location')->nullable();\n            \$table->string('contact_number')->nullable();",
    'create_emergency_alerts_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->decimal('latitude', 10, 8)->nullable();\n            \$table->decimal('longitude', 11, 8)->nullable();\n            \$table->string('status')->default('pending');\n            \$table->text('notes')->nullable();",
    'create_wellness_logs_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->date('log_date');\n            \$table->decimal('sleep_hours', 4, 2)->nullable();\n            \$table->integer('steps')->nullable();\n            \$table->integer('water_intake_ml')->nullable();\n            \$table->integer('stress_level')->nullable();",
    'create_nutrition_logs_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->date('log_date');\n            \$table->string('meal_type');\n            \$table->text('description');\n            \$table->integer('calories_estimated')->nullable();\n            \$table->text('doctor_notes')->nullable();",
    'create_dependents_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->string('name');\n            \$table->date('date_of_birth');\n            \$table->string('relationship');\n            \$table->string('blood_group')->nullable();\n            \$table->text('medical_history')->nullable();",
    'create_symptom_checks_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->text('symptoms_reported');\n            \$table->text('ai_suggested_conditions')->nullable();\n            \$table->text('ai_recommended_specialties')->nullable();",
    'create_certificates_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->string('type');\n            \$table->date('start_date')->nullable();\n            \$table->date('end_date')->nullable();\n            \$table->text('remarks')->nullable();\n            \$table->string('pdf_path')->nullable();",
    'create_note_templates_table.php' => "\$table->id();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->string('title');\n            \$table->text('content');",
    'create_chatbot_sessions_table.php' => "\$table->id();\n            \$table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();\n            \$table->string('session_id')->unique();\n            \$table->json('conversation_history')->nullable();",
    'create_rooms_table.php' => "\$table->id();\n            \$table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();\n            \$table->string('room_number');\n            \$table->string('type');\n            \$table->integer('capacity');\n            \$table->string('status')->default('available');",
    'create_bed_assignments_table.php' => "\$table->id();\n            \$table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->string('bed_number');\n            \$table->date('admission_date');\n            \$table->date('discharge_date')->nullable();\n            \$table->string('status')->default('active');",
    'create_medications_table.php' => "\$table->id();\n            \$table->string('name');\n            \$table->string('dosage_form');\n            \$table->string('strength');\n            \$table->string('manufacturer')->nullable();",
    'create_inventory_levels_table.php' => "\$table->id();\n            \$table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete();\n            \$table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();\n            \$table->integer('quantity');\n            \$table->integer('reorder_threshold');",
    'create_dispensing_records_table.php' => "\$table->id();\n            \$table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();\n            \$table->integer('quantity_dispensed');\n            \$table->text('instructions')->nullable();",
    'create_shifts_table.php' => "\$table->id();\n            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();\n            \$table->date('shift_date');\n            \$table->time('start_time');\n            \$table->time('end_time');\n            \$table->string('department')->nullable();",
    'create_leave_requests_table.php' => "\$table->id();\n            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();\n            \$table->date('start_date');\n            \$table->date('end_date');\n            \$table->text('reason');\n            \$table->string('status')->default('pending');",
    'create_equipment_table.php' => "\$table->id();\n            \$table->string('name');\n            \$table->string('serial_number')->unique();\n            \$table->string('department');\n            \$table->string('status')->default('operational');\n            \$table->date('next_maintenance_date')->nullable();",
    'create_announcements_table.php' => "\$table->id();\n            \$table->string('title');\n            \$table->text('content');\n            \$table->string('target_role')->nullable();\n            \$table->foreignId('target_branch_id')->nullable()->constrained('branches')->cascadeOnDelete();",
    'create_whatsapp_logs_table.php' => "\$table->id();\n            \$table->string('phone_number');\n            \$table->text('message');\n            \$table->string('status');\n            \$table->string('message_id')->nullable();",
    'create_survey_responses_table.php' => "\$table->id();\n            \$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();\n            \$table->integer('rating');\n            \$table->text('comments')->nullable();",
    'create_webauthn_keys_table.php' => "\$table->id();\n            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();\n            \$table->string('name')->nullable();\n            \$table->text('credential_id');\n            \$table->text('public_key');\n            \$table->integer('counter')->default(0);",
    'create_doctor_preferences_table.php' => "\$table->id();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->json('dashboard_layout')->nullable();",
    'create_user_preferences_table.php' => "\$table->id();\n            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();\n            \$table->boolean('dark_mode')->default(false);",
];

foreach ($files as $file) {
    if (strpos($file, '.php') === false) continue;

    foreach ($schemas as $key => $schema) {
        if (strpos($file, $key) !== false) {
            $content = file_get_contents($migrationsDir . '/' . $file);
            $content = str_replace("\$table->id();", $schema, $content);
            file_put_contents($migrationsDir . '/' . $file, $content);
            echo "Updated $file\n";
        }
    }
}
echo "Done!\n";
