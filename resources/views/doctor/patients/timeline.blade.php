@extends('layouts.app')
@section('title', 'Patient Timeline — ' . $patient->user->name)
@section('page-title', 'Patient Timeline')
@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
<div class="space-y-6">
    {{-- Patient Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-2xl font-bold text-primary-700 dark:text-primary-300">
            {{ strtoupper(substr($patient->user->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="font-bold text-gray-900 dark:text-white text-lg">{{ $patient->user->name }}</h2>
            <p class="text-sm text-gray-500">
                Age {{ $patient->age ?? 'N/A' }} · {{ ucfirst($patient->gender ?? '—') }} · Blood Group: {{ $patient->blood_group ?? '—' }}
            </p>
        </div>
        @if($vitals->count() > 0)
        @php $v = $vitals->first(); @endphp
        <div class="hidden md:grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-xs text-gray-400">BP</p>
                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $v->blood_pressure_systolic ?? '—' }}/{{ $v->blood_pressure_diastolic ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">HR</p>
                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $v->heart_rate ?? '—' }} bpm</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Weight</p>
                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $v->weight ?? '—' }} kg</p>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Timeline --}}
        <div class="lg:col-span-2 space-y-3">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100">Clinical Timeline</h3>
            @forelse($timeline as $item)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex gap-4">
                <div class="shrink-0 mt-0.5">
                    @php
                        $icons = [
                            'appointment' => ['fa-calendar-check', 'bg-blue-100 text-blue-600'],
                            'soap'        => ['fa-notes-medical', 'bg-purple-100 text-purple-600'],
                            'prescription'=> ['fa-prescription-bottle-alt', 'bg-green-100 text-green-600'],
                            'lab'         => ['fa-vials', 'bg-yellow-100 text-yellow-600'],
                            'record'      => ['fa-file-medical', 'bg-red-100 text-red-600'],
                        ];
                        [$icon, $cls] = $icons[$item['type']] ?? ['fa-circle', 'bg-gray-100 text-gray-500'];
                    @endphp
                    <div class="w-8 h-8 rounded-lg {{ $cls }} flex items-center justify-center">
                        <i class="fas {{ $icon }} text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900 dark:text-white text-sm capitalize">{{ str_replace('_', ' ', $item['type']) }}</p>
                        <p class="text-xs text-gray-400">
                            {{ is_string($item['date']) ? \Carbon\Carbon::parse($item['date'])->format('d M Y') : (is_object($item['date']) ? $item['date']->format('d M Y') : '—') }}
                        </p>
                    </div>
                    @php $d = $item['data']; @endphp
                    @if($item['type'] === 'appointment')
                        <p class="text-xs text-gray-500 mt-0.5">Status: {{ ucfirst($d->status) }} · {{ $d->reason ?? 'N/A' }}</p>
                    @elseif($item['type'] === 'soap')
                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $d->assessment ?? 'Assessment not recorded.' }}</p>
                    @elseif($item['type'] === 'prescription')
                        <p class="text-xs text-gray-500 mt-0.5">{{ $d->items->count() }} medication(s)</p>
                    @elseif($item['type'] === 'lab')
                        <p class="text-xs text-gray-500 mt-0.5">{{ $d->test_type }} · {{ ucfirst($d->status) }}</p>
                    @elseif($item['type'] === 'record')
                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $d->type }}: {{ Str::limit($d->details, 60) }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center text-gray-400">
                <i class="fas fa-clock text-3xl mb-3 text-gray-200 dark:text-gray-600"></i>
                <p>No timeline entries found.</p>
            </div>
            @endforelse
        </div>

        {{-- Vitals Mini Chart --}}
        <div class="space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100">Recent Vitals</h3>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4">
                @if($vitals->count() > 0)
                <canvas id="vitalsChart" height="180"></canvas>
                @else
                <p class="text-sm text-gray-400 text-center py-8">No vitals recorded.</p>
                @endif
            </div>
            @if($vitals->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 space-y-2 text-sm">
                @php $lv = $vitals->first(); @endphp
                <div class="flex justify-between"><span class="text-gray-500">Temperature</span><span class="font-medium">{{ $lv->temperature ? $lv->temperature . '°C' : '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">SpO₂</span><span class="font-medium">{{ $lv->oxygen_saturation ? $lv->oxygen_saturation . '%' : '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Blood Glucose</span><span class="font-medium">{{ $lv->blood_glucose ? $lv->blood_glucose . ' mg/dL' : '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">BMI</span><span class="font-medium {{ $lv->bmi ? ($lv->bmi < 18.5 || $lv->bmi > 25 ? 'text-red-500' : 'text-green-600') : '' }}">{{ $lv->bmi ?? '—' }}</span></div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
@if($vitals->count() > 0)
const vd = @json($vitals->reverse()->values());
const labels = vd.map(v => new Date(v.recorded_at).toLocaleDateString('en-IN', {day:'2-digit',month:'short'}));
new Chart(document.getElementById('vitalsChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { label: 'Systolic BP', data: vd.map(v => v.blood_pressure_systolic), borderColor: 'rgb(239,68,68)', tension: 0.4 },
            { label: 'Diastolic BP', data: vd.map(v => v.blood_pressure_diastolic), borderColor: 'rgb(59,130,246)', tension: 0.4 },
            { label: 'Heart Rate', data: vd.map(v => v.heart_rate), borderColor: 'rgb(34,197,94)', tension: 0.4 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } }, scales: { y: { beginAtZero: false } } }
});
@endif
</script>
@endsection
