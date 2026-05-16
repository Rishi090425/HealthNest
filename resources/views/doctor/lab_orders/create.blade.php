@extends('layouts.app')
@section('title', 'New Lab Order')
@section('page-title', 'New Lab Order')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('doctor.lab-orders.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient *</label>
                <select name="patient_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">Select Patient...</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Date *</label>
                <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type *</label>
                <input type="text" name="test_type" placeholder="e.g. Complete Blood Count, HbA1c, X-Ray Chest..." required
                       value="{{ old('test_type') }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clinical Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="notes" rows="3" placeholder="Clinical indication or special instructions..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('notes') }}</textarea>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('doctor.lab-orders.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-vials mr-2"></i>Create Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
