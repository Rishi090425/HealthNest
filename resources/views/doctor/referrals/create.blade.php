@extends('layouts.app')
@section('title', 'Send Referral')
@section('page-title', 'Send Referral')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('doctor.referrals.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient *</label>
                <select name="patient_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">Select Patient...</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                    @endforeach
                </select>
                @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Refer To Doctor *</label>
                <select name="referred_doctor_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">Select Doctor...</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ old('referred_doctor_id') == $doc->id ? 'selected' : '' }}>
                        Dr. {{ $doc->user->name }} ({{ $doc->specialization }})
                    </option>
                    @endforeach
                </select>
                @error('referred_doctor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason & Case Notes *</label>
                <textarea name="reason" rows="5" required placeholder="Describe the reason for referral and relevant clinical notes..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('reason') }}</textarea>
                @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3">
                <a href="{{ route('doctor.referrals.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-share-square mr-2"></i>Send Referral
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
