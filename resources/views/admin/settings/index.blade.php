@extends('layouts.app')
@section('title', 'System Settings')
@section('page-title', 'System Settings')
@section('content')
<div class="max-w-3xl mx-auto">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">
            {{-- Branding --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="fas fa-palette text-primary-500"></i> Branding
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Site Name *</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Health Nest' }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tagline</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Currency Code *</label>
                        <input type="text" name="currency" value="{{ $settings['currency'] ?? 'INR' }}" maxlength="3" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Logo (PNG/JPG)</label>
                        <input type="file" name="logo" accept=".png,.jpg,.jpeg"
                               class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                </div>
            </div>

            {{-- Contact --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="fas fa-address-card text-primary-500"></i> Contact Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Contact Phone</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- Payments --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="fas fa-wallet text-primary-500"></i> Payment Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Hospital UPI ID (for Scanner/QR payments) *</label>
                        <input type="text" name="upi_id" value="{{ $settings['upi_id'] ?? 'rishi.kumar14125@okaxis' }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="fas fa-bell text-primary-500"></i> Notification Templates
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Appointment Reminder (hours before)</label>
                        <input type="number" name="appointment_reminder_hours" value="{{ $settings['appointment_reminder_hours'] ?? 24 }}" min="1" max="72"
                               class="w-full md:w-32 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Appointment Reminder Email Template</label>
                        <textarea name="appointment_reminder_template" rows="4"
                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none font-mono text-xs">{{ $settings['appointment_reminder_template'] ?? 'Dear {patient_name}, your appointment with Dr. {doctor_name} is scheduled on {date} at {time}.' }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Available placeholders: {patient_name}, {doctor_name}, {date}, {time}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Cancellation Template</label>
                        <textarea name="cancellation_template" rows="3"
                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none font-mono text-xs">{{ $settings['cancellation_template'] ?? 'Dear {patient_name}, your appointment on {date} has been cancelled.' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                    <i class="fas fa-save mr-2"></i>Save All Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
