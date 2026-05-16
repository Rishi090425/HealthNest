@extends('layouts.app')
@section('title', 'New Prescription')
@section('page-title', 'New Prescription')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('doctor.prescriptions.store') }}" id="prescription-form">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient *</label>
                    <select name="patient_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">Select Patient...</option>
                        @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date *</label>
                    <input type="date" name="prescription_date" value="{{ old('prescription_date', date('Y-m-d')) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
            </div>

            {{-- Medication Items --}}
            <div class="mb-5">
                <div class="flex justify-between items-center mb-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Medications *</label>
                    <button type="button" id="add-item" class="text-primary-600 hover:text-primary-800 text-xs font-medium">
                        <i class="fas fa-plus mr-1"></i>Add Medication
                    </button>
                </div>
                <div id="items-container" class="space-y-3">
                    <div class="item-row grid grid-cols-5 gap-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <div class="col-span-2">
                            <input type="text" name="items[0][medication_name]" placeholder="Medication name *" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                        <div>
                            <input type="text" name="items[0][dosage]" placeholder="Dosage *" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                        <div>
                            <input type="text" name="items[0][frequency]" placeholder="Frequency *" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                        <div>
                            <input type="text" name="items[0][duration]" placeholder="Duration *" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="notes" rows="2" placeholder="Additional notes..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('doctor.prescriptions.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-save mr-2"></i>Issue Prescription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let idx = 1;
document.getElementById('add-item').addEventListener('click', () => {
    const tpl = `<div class="item-row grid grid-cols-5 gap-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 relative">
        <button type="button" onclick="this.closest('.item-row').remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600">×</button>
        <div class="col-span-2"><input type="text" name="items[${idx}][medication_name]" placeholder="Medication name *" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none"></div>
        <div><input type="text" name="items[${idx}][dosage]" placeholder="Dosage *" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none"></div>
        <div><input type="text" name="items[${idx}][frequency]" placeholder="Frequency *" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none"></div>
        <div><input type="text" name="items[${idx}][duration]" placeholder="Duration *" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none"></div>
    </div>`;
    document.getElementById('items-container').insertAdjacentHTML('beforeend', tpl);
    idx++;
});
</script>
@endsection
