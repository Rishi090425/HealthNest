@extends('layouts.app')
@section('title', 'Prescription Detail')
@section('page-title', 'Prescription')
@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-start mb-5">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white text-lg">Digital Prescription</h2>
                <p class="text-sm text-gray-500">
                    Patient: {{ $prescription->patient->user->name }} ·
                    Date: {{ $prescription->prescription_date->format('d M Y') }}
                </p>
            </div>
            <a href="{{ route('doctor.prescriptions.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border rounded-xl overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500">
                    <tr>
                        <th class="px-4 py-2.5 text-left">Medication</th>
                        <th class="px-4 py-2.5 text-left">Dosage</th>
                        <th class="px-4 py-2.5 text-left">Frequency</th>
                        <th class="px-4 py-2.5 text-left">Duration</th>
                        <th class="px-4 py-2.5 text-left">Instructions</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($prescription->items as $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-2.5 font-semibold text-gray-900 dark:text-white">{{ $item->medication_name }}</td>
                        <td class="px-4 py-2.5">{{ $item->dosage }}</td>
                        <td class="px-4 py-2.5">{{ $item->frequency }}</td>
                        <td class="px-4 py-2.5">{{ $item->duration }}</td>
                        <td class="px-4 py-2.5 text-gray-500">{{ $item->instructions ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($prescription->notes)
        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
            <p class="text-xs font-medium text-yellow-700 dark:text-yellow-300 mb-1">Additional Notes</p>
            <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $prescription->notes }}</p>
        </div>
        @endif

        <div class="mt-5 pt-4 border-t dark:border-gray-700 flex justify-between items-center text-sm text-gray-500">
            <span>Issued by Dr. {{ $prescription->doctor->user->name }}</span>
            <span>{{ $prescription->created_at->format('d M Y, H:i') }}</span>
        </div>
    </div>
</div>
@endsection
