@extends('layouts.app')
@section('title', 'My Profile & Fee')
@section('page-title', 'My Profile & Fee')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Profile Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-2xl font-bold text-white shadow-md">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                <p class="text-sm text-blue-600 font-medium mt-0.5">{{ $doctor->specialization }}</p>
            </div>
        </div>

        {{-- Current Fee Highlight --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-5 mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Current Consultation Fee</p>
                <p class="text-3xl font-extrabold text-blue-700 mt-1">₹{{ number_format($doctor->consultation_fee) }}</p>
                <p class="text-xs text-gray-400 mt-1">Patients see this fee when booking</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-rupee-sign text-blue-600 text-xl"></i>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('doctor.profile.update') }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Fee (most prominent) --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <label class="block text-sm font-bold text-amber-800 mb-2">
                    <i class="fas fa-rupee-sign mr-1"></i> Set Consultation Fee (₹) *
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold text-lg">₹</span>
                    <input type="number" name="consultation_fee" id="fee-input"
                        value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                        min="0" max="99999" step="50" required
                        class="w-full pl-10 pr-4 py-3 border-2 border-amber-300 focus:border-amber-500 rounded-xl text-lg font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white">
                </div>
                <p class="text-xs text-amber-600 mt-1.5">This is what patients will see when booking an appointment with you.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization *</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qualification *</label>
                    <input type="text" name="qualification" value="{{ old('qualification', $doctor->qualification) }}" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Experience (Years) *</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}"
                        min="0" max="60" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">About / Bio</label>
                    <textarea name="bio" rows="3"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        placeholder="Brief description about your expertise and approach...">{{ old('bio', $doctor->bio) }}</textarea>
                </div>
            </div>

            <button type="submit" id="save-profile-btn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Save Profile & Update Fee
            </button>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
        <i class="fas fa-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
        <div class="text-sm text-blue-700">
            <strong>Note:</strong> Your consultation fee is publicly visible to patients when they book appointments.
            Fees are set by you as a doctor, or can also be updated by the administrator.
            Changes take effect immediately for new bookings.
        </div>
    </div>
</div>
@endsection
