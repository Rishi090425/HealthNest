@extends('layouts.app')
@section('title', 'Medical Records')
@section('page-title', 'Medical Records')

@section('content')
<div class="space-y-6">
    {{-- Tab Pills --}}
    <div x-data="{ tab: 'records' }" class="space-y-6">
        <div class="flex gap-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-2 w-fit">
            <button @click="tab='records'"  :class="tab==='records'  ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" class="px-5 py-2 rounded-xl text-sm font-medium transition-all"><i class="fas fa-file-medical mr-2"></i>Records</button>
            <button @click="tab='rx'"       :class="tab==='rx'       ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" class="px-5 py-2 rounded-xl text-sm font-medium transition-all"><i class="fas fa-prescription-bottle-alt mr-2"></i>Prescriptions</button>
            <button @click="tab='labs'"     :class="tab==='labs'     ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" class="px-5 py-2 rounded-xl text-sm font-medium transition-all"><i class="fas fa-vials mr-2"></i>Lab Results</button>
        </div>

        {{-- Medical Records --}}
        <div x-show="tab==='records'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Doctor</th>
                        <th class="px-4 py-3 text-left">Details</th>
                        <th class="px-4 py-3 text-left">File</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($records as $rec)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3">{{ $rec->record_date->format('d M Y') }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">{{ ucfirst($rec->type) }}</span></td>
                        <td class="px-4 py-3">Dr. {{ $rec->doctor?->user?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 max-w-xs truncate text-gray-600 dark:text-gray-400">{{ $rec->details }}</td>
                        <td class="px-4 py-3">
                            @if($rec->file_path)
                                <a href="{{ Storage::url($rec->file_path) }}" target="_blank" class="text-primary-600 hover:underline text-xs"><i class="fas fa-download mr-1"></i>Download</a>
                            @else <span class="text-gray-400 text-xs">—</span> @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No medical records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $records->links() }}</div>
        </div>

        {{-- Prescriptions --}}
        <div x-show="tab==='rx'" class="space-y-4">
            @forelse($prescriptions as $rx)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Dr. {{ $rx->doctor->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $rx->prescription_date->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('patient.prescriptions.download', $rx) }}" class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">Prescription</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border rounded-lg overflow-hidden">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Medication</th>
                                <th class="px-3 py-2 text-left">Dosage</th>
                                <th class="px-3 py-2 text-left">Frequency</th>
                                <th class="px-3 py-2 text-left">Duration</th>
                                <th class="px-3 py-2 text-left">Instructions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @foreach($rx->items as $item)
                            <tr>
                                <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">{{ $item->medication_name }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $item->dosage }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $item->frequency }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $item->duration }}</td>
                                <td class="px-3 py-2 text-gray-500">{{ $item->instructions ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($rx->notes)
                <p class="mt-3 text-sm text-gray-500 italic">Note: {{ $rx->notes }}</p>
                @endif
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 text-center text-gray-400">No prescriptions found.</div>
            @endforelse
            <div>{{ $prescriptions->links() }}</div>
        </div>

        {{-- Lab Results --}}
        <div x-show="tab==='labs'" class="space-y-4">
            @forelse($labOrders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $order->test_type }}</p>
                        <p class="text-sm text-gray-500">Ordered by Dr. {{ $order->doctor->user->name }} · {{ $order->order_date->format('d M Y') }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                @if($order->results->count() > 0)
                    @foreach($order->results as $result)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mt-2">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div><p class="text-gray-400 text-xs">Result</p><p class="font-medium">{{ $result->result_value }}</p></div>
                            <div><p class="text-gray-400 text-xs">Normal Range</p><p class="font-medium">{{ $result->normal_range ?? 'N/A' }}</p></div>
                            <div><p class="text-gray-400 text-xs">Flag</p>
                                @if($result->flag)
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $result->flag === 'normal' ? 'bg-green-100 text-green-700' : ($result->flag === 'critical' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700') }}">
                                    {{ ucfirst($result->flag) }}
                                </span>
                                @else <p>—</p> @endif
                            </div>
                            <div><p class="text-gray-400 text-xs">Date</p><p class="font-medium">{{ $result->result_date->format('d M Y') }}</p></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-400 mt-2">Results not available yet.</p>
                @endif
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 text-center text-gray-400">No lab orders found.</div>
            @endforelse
            <div>{{ $labOrders->links() }}</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
