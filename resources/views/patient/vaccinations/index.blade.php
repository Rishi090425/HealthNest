@extends('layouts.app')
@section('title', 'Vaccination Tracker')
@section('page-title', 'My Vaccination History')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Record Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border-t-4 border-blue-500 sticky top-24">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Log New Vaccine</h3>
                <form method="POST" action="{{ route('patient.vaccinations.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Vaccine Name *</label>
                        <input type="text" name="vaccine_name" required placeholder="e.g. COVID-19, Flu, Hep B"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Date Received *</label>
                        <input type="date" name="date_received" required
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Provider</label>
                        <input type="text" name="provider" placeholder="Hospital or Clinic Name"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Next Due Date</label>
                        <input type="date" name="next_due_date"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg shadow-primary-200">
                        Add to Log
                    </button>
                </form>
            </div>
        </div>

        {{-- Records Table --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 dark:text-white">Vaccination Records</h3>
                    <button onclick="window.print()" class="text-[10px] font-bold text-primary-600 hover:underline uppercase tracking-widest">Download Report</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 text-left">
                            <tr>
                                <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Vaccine</th>
                                <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Date</th>
                                <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Next Due</th>
                                <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($vaccinations as $v)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $v->vaccine_name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $v->provider ?? 'Not specified' }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $v->date_received->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($v->next_due_date)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $v->next_due_date->isFuture() ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $v->next_due_date->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('patient.vaccinations.destroy', $v) }}" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">No vaccination records logged.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .lg\:col-span-2, .lg\:col-span-2 * { visibility: visible; }
    .lg\:col-span-2 { position: absolute; left: 0; top: 0; width: 100%; }
    .text-right { display: none; }
}
</style>
@endsection
