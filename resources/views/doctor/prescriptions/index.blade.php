@extends('layouts.app')
@section('title', 'Prescriptions')
@section('page-title', 'Prescriptions')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Issue digital prescriptions saved to patient records.</p>
        <a href="{{ route('doctor.prescriptions.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>New Prescription
        </a>
    </div>

    @forelse($prescriptions as $rx)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $rx->patient->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $rx->prescription_date->format('d M Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('doctor.prescriptions.show', $rx) }}" class="text-xs text-primary-600 hover:underline font-medium"><i class="fas fa-eye mr-1"></i>View</a>
                <form method="POST" action="{{ route('doctor.prescriptions.destroy', $rx) }}" onsubmit="return confirm('Delete prescription?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700"><i class="fas fa-trash mr-1"></i>Delete</button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs border rounded-lg overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500">
                    <tr>
                        <th class="px-3 py-2 text-left">Medication</th>
                        <th class="px-3 py-2 text-left">Dosage</th>
                        <th class="px-3 py-2 text-left">Frequency</th>
                        <th class="px-3 py-2 text-left">Duration</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($rx->items->take(3) as $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-3 py-1.5 font-medium">{{ $item->medication_name }}</td>
                        <td class="px-3 py-1.5">{{ $item->dosage }}</td>
                        <td class="px-3 py-1.5">{{ $item->frequency }}</td>
                        <td class="px-3 py-1.5">{{ $item->duration }}</td>
                    </tr>
                    @endforeach
                    @if($rx->items->count() > 3)
                    <tr><td colspan="4" class="px-3 py-1.5 text-gray-400 italic">+{{ $rx->items->count() - 3 }} more items</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center text-gray-400">
        <i class="fas fa-prescription-bottle-alt text-4xl mb-3 text-gray-200 dark:text-gray-600"></i>
        <p>No prescriptions issued yet.</p>
    </div>
    @endforelse
    <div>{{ $prescriptions->links() }}</div>
</div>
@endsection
