<?php

$migrationsDir = __DIR__ . '/database/migrations';
$files = scandir($migrationsDir);

$schemas = [
    'create_departments_table.php' => "\$table->string('name');\n            \$table->text('description')->nullable();",
    'create_specialties_table.php' => "\$table->string('name');\n            \$table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();\n            \$table->text('description')->nullable();",
    'create_medical_records_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();\n            \$table->date('record_date');\n            \$table->string('type');\n            \$table->text('details');\n            \$table->string('file_path')->nullable();",
    'create_prescriptions_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();\n            \$table->date('prescription_date');\n            \$table->text('notes')->nullable();",
    'create_prescription_items_table.php' => "\$table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();\n            \$table->string('medication_name');\n            \$table->string('dosage');\n            \$table->string('frequency');\n            \$table->string('duration');\n            \$table->text('instructions')->nullable();",
    'create_lab_orders_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->date('order_date');\n            \$table->string('test_type');\n            \$table->string('status')->default('pending');\n            \$table->text('notes')->nullable();",
    'create_lab_results_table.php' => "\$table->foreignId('lab_order_id')->constrained('lab_orders')->cascadeOnDelete();\n            \$table->date('result_date');\n            \$table->text('result_value');\n            \$table->string('normal_range')->nullable();\n            \$table->string('flag')->nullable();\n            \$table->string('report_path')->nullable();",
    'create_soap_notes_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();\n            \$table->text('subjective')->nullable();\n            \$table->text('objective')->nullable();\n            \$table->text('assessment')->nullable();\n            \$table->text('plan')->nullable();",
    'create_messages_table.php' => "\$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();\n            \$table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();\n            \$table->text('content');\n            \$table->timestamp('read_at')->nullable();",
    'create_vitals_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->dateTime('recorded_at');\n            \$table->integer('blood_pressure_systolic')->nullable();\n            \$table->integer('blood_pressure_diastolic')->nullable();\n            \$table->integer('heart_rate')->nullable();\n            \$table->decimal('temperature', 4, 2)->nullable();\n            \$table->integer('respiratory_rate')->nullable();\n            \$table->decimal('weight', 5, 2)->nullable();\n            \$table->decimal('height', 5, 2)->nullable();\n            \$table->integer('oxygen_saturation')->nullable();\n            \$table->integer('blood_glucose')->nullable();",
    'create_invoices_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();\n            \$table->decimal('amount', 10, 2);\n            \$table->string('status')->default('unpaid');\n            \$table->date('due_date')->nullable();\n            \$table->date('issued_date')->nullable();",
    'create_payments_table.php' => "\$table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();\n            \$table->decimal('amount', 10, 2);\n            \$table->string('payment_method');\n            \$table->string('transaction_id')->nullable();\n            \$table->dateTime('payment_date');",
    'create_reviews_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->foreignId('appointment_id')->nullable()->constrained('appointments')->cascadeOnDelete();\n            \$table->integer('rating');\n            \$table->text('comment')->nullable();",
    'create_referrals_table.php' => "\$table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();\n            \$table->foreignId('referring_doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->foreignId('referred_doctor_id')->constrained('doctors')->cascadeOnDelete();\n            \$table->text('reason');\n            \$table->string('status')->default('pending');",
    'create_audit_logs_table.php' => "\$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();\n            \$table->string('action');\n            \$table->string('model_type')->nullable();\n            \$table->unsignedBigInteger('model_id')->nullable();\n            \$table->json('changes')->nullable();\n            \$table->string('ip_address')->nullable();",
    'create_settings_table.php' => "\$table->string('key')->unique();\n            \$table->text('value')->nullable();",
];

foreach ($files as $file) {
    if (strpos($file, '.php') === false) continue;

    foreach ($schemas as $key => $schema) {
        if (strpos($file, $key) !== false) {
            $content = file_get_contents($migrationsDir . '/' . $file);
            $content = str_replace("\$table->id();", "\$table->id();\n            " . $schema, $content);
            file_put_contents($migrationsDir . '/' . $file, $content);
            echo "Fixed $file\n";
        }
    }
}
echo "Done!\n";
