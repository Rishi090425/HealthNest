@extends('layouts.app')
@section('title', 'Create Invoice')
@section('page-title', 'Create New Invoice')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.invoices.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Patient *</label>
                <select name="patient_id" id="patient_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">Choose a patient...</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ (old('patient_id') == $p->id || ($selected_appointment && $selected_appointment->patient_id == $p->id)) ? 'selected' : '' }}>
                        {{ $p->user->name }} ({{ $p->user->email }})
                    </option>
                    @endforeach
                </select>
                @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link to Appointment <span class="text-gray-400 font-normal">(optional)</span></label>
                <select name="appointment_id" id="appointment_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">No appointment...</option>
                    @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}" data-patient="{{ $appt->patient_id }}" data-fee="{{ $appt->doctor->consultation_fee ?? 500 }}"
                        {{ (old('appointment_id') == $appt->id || ($selected_appointment && $selected_appointment->id == $appt->id)) ? 'selected' : '' }}>
                        {{ $appt->doctor->user->display_name }} — {{ $appt->appointment_date->format('d M Y') }}
                    </option>
                    @endforeach
                </select>
                @error('appointment_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (₹) *</label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $selected_appointment ? ($selected_appointment->doctor->consultation_fee ?? 500) : '') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                <textarea name="notes" rows="3" placeholder="Additional details for the invoice..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.invoices.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-file-invoice mr-2"></i>Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-fill amount and patient when appointment is selected
document.getElementById('appointment_id')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if(opt.value) {
        document.getElementById('patient_id').value = opt.dataset.patient;
        document.getElementById('amount').value = opt.dataset.fee;
    }
});
</script>
@endsection
