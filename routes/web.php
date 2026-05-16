<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Doctor;
use App\Http\Controllers\Patient;
use Illuminate\Support\Facades\Route;

// Welcome
Route::get('/', fn() => view('welcome'))->name('home');

Route::get('/generate-models', function () {
    $models = ['Certificate', 'NoteTemplate', 'ChatbotSession', 'Room', 'BedAssignment', 'Medication', 'InventoryLevel', 'DispensingRecord', 'Shift', 'LeaveRequest', 'Equipment', 'Announcement', 'WhatsappLog', 'SurveyResponse', 'WebauthnKey', 'DoctorPreference', 'UserPreference'];
    $out = '';
    foreach($models as $model) {
        \Illuminate\Support\Facades\Artisan::call('make:model', ['name' => $model, '-m' => true]);
        $out .= \Illuminate\Support\Facades\Artisan::output() . "<br>";
    }
    return $out;
});
// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::post('/user/preferences/dark-mode', function (\Illuminate\Http\Request $request) {
        $pref = \App\Models\UserPreference::firstOrCreate(['user_id' => auth()->id()]);
        $pref->dark_mode = $request->dark_mode;
        $pref->save();
        return response()->json(['success' => true]);
    })->name('preferences.dark-mode');

    // Video Consultation
    Route::get('/video-room/{appointment}',         [App\Http\Controllers\VideoRoomController::class, 'show'])->name('video-room.show');
    Route::post('/video-room/{appointment}/generate', [App\Http\Controllers\VideoRoomController::class, 'generate'])->name('video-room.generate');

    // Symptom Checker Bot
    Route::post('/symptom-checker/check', [App\Http\Controllers\SymptomCheckerController::class, 'check'])->name('symptom-checker.check');
});
// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Doctors
    Route::resource('doctors', Admin\DoctorController::class)->except(['show']);

    // Patients
    Route::get('/patients',             [Admin\PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{patient}',   [Admin\PatientController::class, 'show'])->name('patients.show');
    Route::delete('/patients/{patient}',[Admin\PatientController::class, 'destroy'])->name('patients.destroy');

    // Appointments — Admin can only approve or reject (NOT complete — that is the doctor's job)
    Route::get('/appointments',                                [Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/approve',        [Admin\AppointmentController::class, 'approve'])->name('appointments.approve');
    Route::patch('/appointments/{appointment}/reject',         [Admin\AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::patch('/appointments/{appointment}/status',         [Admin\AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Emergency Alerts
    Route::get('/emergency-alerts',                      [Admin\EmergencyAlertController::class, 'index'])->name('emergency-alerts.index');
    Route::patch('/emergency-alerts/{alert}/respond',    [Admin\EmergencyAlertController::class, 'respond'])->name('emergency-alerts.respond');
    Route::patch('/emergency-alerts/{alert}/resolve',    [Admin\EmergencyAlertController::class, 'resolve'])->name('emergency-alerts.resolve');

    // Virtual Queue
    Route::get('/queue',                   [Admin\WalkInQueueController::class, 'index'])->name('queue.index');
    Route::post('/queue/call-next',        [Admin\WalkInQueueController::class, 'callNext'])->name('queue.call-next');
    Route::patch('/queue/{queue}/status',  [Admin\WalkInQueueController::class, 'updateStatus'])->name('queue.status');

    // Medicine Inventory
    Route::get('/inventory',                   [Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create',            [Admin\InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory',                  [Admin\InventoryController::class, 'store'])->name('inventory.store');
    Route::patch('/inventory/{medication}',    [Admin\InventoryController::class, 'updateStock'])->name('inventory.update-stock');

    // Complaints
    Route::get('/complaints',                         [Admin\ComplaintController::class, 'index'])->name('complaints.index');
    Route::patch('/complaints/{complaint}/respond',   [Admin\ComplaintController::class, 'respond'])->name('complaints.respond');

    // Announcements
    Route::resource('announcements', Admin\AnnouncementController::class)->except(['show']);

    // Departments & Specialties
    Route::resource('departments', Admin\DepartmentController::class)->except(['show']);
    Route::resource('specialties',  Admin\SpecialtyController::class)->except(['show']);

    // Invoices & Payments
    Route::resource('invoices', Admin\InvoiceController::class)->only(['index', 'create', 'store', 'show']);

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');

    // Audit Log
    Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

    // System Settings
    Route::get('/settings',  [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    // Leave Management
    Route::get('/leaves',            [Admin\LeaveController::class, 'index'])->name('leaves.index');
    Route::patch('/leaves/{leave}',  [Admin\LeaveController::class, 'update'])->name('leaves.update');

    // WhatsApp Reminders
    Route::post('/appointments/{appointment}/whatsapp', function (\App\Models\Appointment $appointment) {
        \App\Services\WhatsappService::sendAppointmentReminder($appointment);
        return back()->with('success', 'WhatsApp reminder sent successfully!');
    })->name('appointments.whatsapp');
});

// ─── Doctor Routes ────────────────────────────────────────────────────────────
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', [Doctor\DashboardController::class, 'index'])->name('dashboard');

    // Appointments — Doctor controls approval AND completion
    Route::get('/appointments',                                  [Doctor\AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/approve',          [Doctor\AppointmentController::class, 'approve'])->name('appointments.approve');
    Route::patch('/appointments/{appointment}/reject',           [Doctor\AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::patch('/appointments/{appointment}/complete',         [Doctor\AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::patch('/appointments/{appointment}/status',           [Doctor\AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Consultations
    Route::get('/appointments/{appointment}/consultation',      [Doctor\ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/appointments/{appointment}/consultation',     [Doctor\ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/appointments/{appointment}/consultation/view', [Doctor\ConsultationController::class, 'show'])->name('consultations.show');

    // Availability
    Route::get('/availability',  [Doctor\AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [Doctor\AvailabilityController::class, 'update'])->name('availability.update');

    // Profile & Fee Management
    Route::get('/profile',  [Doctor\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile',  [Doctor\ProfileController::class, 'update'])->name('profile.update');

    // SOAP Notes
    Route::resource('soap-notes', Doctor\SoapNoteController::class);

    // Prescriptions
    Route::resource('prescriptions', Doctor\PrescriptionController::class)->except(['edit', 'update']);

    // Lab Orders
    Route::resource('lab-orders', Doctor\LabOrderController::class)->except(['edit', 'update']);
    Route::post('/lab-orders/{labOrder}/annotate', [Doctor\LabOrderController::class, 'annotate'])->name('lab-orders.annotate');

    // Referrals
    Route::resource('referrals', Doctor\ReferralController::class)->only(['index', 'create', 'store', 'show']);

    // Messages
    Route::get('/messages',          [Doctor\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}',   [Doctor\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages',         [Doctor\MessageController::class, 'store'])->name('messages.store');

    // Leave Management (Doctor)
    Route::get('/leaves',            [Doctor\LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves',           [Doctor\LeaveController::class, 'store'])->name('leaves.store');
    Route::delete('/leaves/{leave}', [Doctor\LeaveController::class, 'destroy'])->name('leaves.destroy');

    // Patient Timeline
    Route::get('/patients/{patient}/timeline', [Doctor\PatientTimelineController::class, 'show'])->name('patients.timeline');
});

// ─── Patient Routes ───────────────────────────────────────────────────────────
Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/dashboard', [Patient\DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::get('/appointments',                  [Patient\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/book',             [Patient\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments',                 [Patient\AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{appointment}', [Patient\AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Profile
    Route::get('/profile',      [Patient\ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [Patient\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [Patient\ProfileController::class, 'update'])->name('profile.update');

    // Complaints & Requests
    Route::get('/complaints',        [Patient\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/new',    [Patient\ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints',       [Patient\ComplaintController::class, 'store'])->name('complaints.store');

    // Medical Records, Prescriptions, Lab Results
    Route::get('/medical-records',         [Patient\MedicalRecordController::class, 'index'])->name('medical-records.index');
    Route::get('/medical-records/{medicalRecord}', [Patient\MedicalRecordController::class, 'show'])->name('medical-records.show');
    Route::get('/medical-records/{medicalRecord}/download', [Patient\MedicalRecordController::class, 'downloadPdf'])->name('medical-records.download');
    Route::get('/prescriptions/{prescription}/download', [Patient\PrescriptionController::class, 'download'])->name('prescriptions.download');

    // Vitals Tracker
    Route::resource('vitals', Patient\VitalController::class)->only(['index', 'create', 'store', 'destroy']);

    // Invoices & Payments
    Route::get('/invoices',              [Patient\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}',    [Patient\InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [Patient\InvoiceController::class, 'pay'])->name('invoices.pay');

    // Razorpay Integration
    Route::post('/invoices/{invoice}/razorpay/order', [Patient\RazorpayController::class, 'createOrder'])->name('razorpay.order');
    Route::post('/invoices/{invoice}/razorpay/verify', [Patient\RazorpayController::class, 'verifyPayment'])->name('razorpay.verify');

    // Reviews
    Route::resource('reviews', Patient\ReviewController::class)->only(['index', 'create', 'store']);

    // Messages
    Route::get('/messages',          [Patient\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}',   [Patient\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages',         [Patient\MessageController::class, 'store'])->name('messages.store');

    Route::post('/sos', [Patient\EmergencyAlertController::class, 'store'])->name('sos.store');

    // Virtual Queue
    Route::get('/queue',             [Patient\WalkInQueueController::class, 'index'])->name('queue.index');
    Route::post('/queue',            [Patient\WalkInQueueController::class, 'store'])->name('queue.store');
    Route::delete('/queue/{queue}',  [Patient\WalkInQueueController::class, 'cancel'])->name('queue.cancel');

    // Vaccination Tracker
    Route::get('/vaccinations',             [Patient\VaccinationController::class, 'index'])->name('vaccinations.index');
    Route::post('/vaccinations',            [Patient\VaccinationController::class, 'store'])->name('vaccinations.store');
    Route::delete('/vaccinations/{vaccination}', [Patient\VaccinationController::class, 'destroy'])->name('vaccinations.destroy');
});