@extends('layouts.app')
@section('title', 'Vitals Tracker')
@section('page-title', 'Vitals Tracker')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- BP Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700/50">
            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="fas fa-heartbeat text-red-500"></i> Blood Pressure Trend
            </h2>
            <div class="h-64"><canvas id="bpChart"></canvas></div>
        </div>

        {{-- Glucose Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700/50">
            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="fas fa-tint text-orange-500"></i> Blood Glucose Trend
            </h2>
            <div class="h-64"><canvas id="glucoseChart"></canvas></div>
        </div>

        {{-- Heart Rate & O2 Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700/50">
            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="fas fa-pulse text-blue-500"></i> Heart Rate & O₂ Saturation
            </h2>
            <div class="h-64"><canvas id="hro2Chart"></canvas></div>
        </div>

        {{-- Stats / Latest Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700/50">
            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-check text-green-500"></i> Latest Assessment
            </h2>
            @if($vitals->count() > 0)
                @php $latest = $vitals->first(); @endphp
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Blood Pressure</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $latest->blood_pressure_systolic ?? '-' }}/{{ $latest->blood_pressure_diastolic ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Heart Rate</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $latest->heart_rate ?? '-' }} <span class="text-xs font-normal">bpm</span></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">O₂ Saturation</p>
                        <p class="text-lg font-bold text-blue-600">{{ $latest->oxygen_saturation ?? '-' }}%</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Blood Glucose</p>
                        <p class="text-lg font-bold text-orange-600">{{ $latest->blood_glucose ?? '-' }} <span class="text-xs font-normal">mg/dL</span></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">BMI</p>
                        <p class="text-lg font-bold text-purple-600">{{ $latest->bmi ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Weight</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $latest->weight ?? '-' }} kg</p>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-stethoscope text-4xl text-gray-200 mb-3"></i>
                    <p class="text-sm text-gray-400">No readings yet. Log your first vital signs to see trends!</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Log New Reading --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Log New Reading</h2>
        <form method="POST" action="{{ route('patient.vitals.store') }}">
            @csrf
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Date & Time</label>
                    <input type="datetime-local" name="recorded_at" value="{{ now()->format('Y-m-d\TH:i') }}" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">BP Systolic (mmHg)</label>
                    <input type="number" name="blood_pressure_systolic" placeholder="e.g. 120" min="50" max="300"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">BP Diastolic (mmHg)</label>
                    <input type="number" name="blood_pressure_diastolic" placeholder="e.g. 80" min="30" max="200"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Heart Rate (bpm)</label>
                    <input type="number" name="heart_rate" placeholder="e.g. 72" min="20" max="300"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" placeholder="e.g. 70.5" min="1" max="500"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Height (cm)</label>
                    <input type="number" step="0.1" name="height" placeholder="e.g. 170" min="30" max="250"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Blood Glucose (mg/dL)</label>
                    <input type="number" name="blood_glucose" placeholder="e.g. 95" min="20" max="600"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">O₂ Saturation (%)</label>
                    <input type="number" name="oxygen_saturation" placeholder="e.g. 98" min="50" max="100"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Reading
                </button>
            </div>
        </form>
    </div>

    {{-- History Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100">Reading History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Date & Time</th>
                        <th class="px-4 py-3 text-left font-medium">BP</th>
                        <th class="px-4 py-3 text-left font-medium">HR</th>
                        <th class="px-4 py-3 text-left font-medium">Weight</th>
                        <th class="px-4 py-3 text-left font-medium">BMI</th>
                        <th class="px-4 py-3 text-left font-medium">Glucose</th>
                        <th class="px-4 py-3 text-left font-medium">SpO₂</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($vitals as $vital)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vital->recorded_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $vital->blood_pressure_systolic ?? '—' }}/{{ $vital->blood_pressure_diastolic ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vital->heart_rate ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vital->weight ? $vital->weight . ' kg' : '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vital->bmi ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vital->blood_glucose ? $vital->blood_glucose . ' mg/dL' : '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vital->oxygen_saturation ? $vital->oxygen_saturation . '%' : '—' }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('patient.vitals.destroy', $vital) }}" onsubmit="return confirm('Delete this reading?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No readings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $vitals->links() }}</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const chartData = @json($chartData);
const labels = chartData.map(v => new Date(v.recorded_at).toLocaleDateString('en-IN', {day:'2-digit', month:'short'}));

// Common Chart Options
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { 
        legend: { 
            position: 'top', 
            labels: { 
                usePointStyle: true, 
                boxWidth: 6, 
                font: { size: 10 } 
            } 
        } 
    },
    scales: { 
        y: { 
            beginAtZero: false, 
            ticks: { font: { size: 10 } } 
        },
        x: { 
            ticks: { font: { size: 10 } } 
        }
    }
};

// BP Chart
new Chart(document.getElementById('bpChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { 
                label: 'Systolic', 
                data: chartData.map(v => v.blood_pressure_systolic), 
                borderColor: '#ef4444', 
                backgroundColor: '#ef444420', 
                tension: 0.4, 
                fill: true, 
                pointRadius: 2 
            },
            { 
                label: 'Diastolic', 
                data: chartData.map(v => v.blood_pressure_diastolic), 
                borderColor: '#3b82f6', 
                backgroundColor: '#3b82f620', 
                tension: 0.4, 
                fill: true, 
                pointRadius: 2 
            }
        ]
    },
    options: commonOptions
});

// Glucose Chart
new Chart(document.getElementById('glucoseChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{ 
            label: 'Glucose (mg/dL)', 
            data: chartData.map(v => v.blood_glucose), 
            borderColor: '#f97316', 
            backgroundColor: '#f9731620', 
            tension: 0.4, 
            fill: true, 
            pointRadius: 3 
        }]
    },
    options: commonOptions
});

// Heart Rate & O2 Chart
new Chart(document.getElementById('hro2Chart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { 
                label: 'Heart Rate', 
                data: chartData.map(v => v.heart_rate), 
                borderColor: '#10b981', 
                tension: 0.4, 
                pointRadius: 2 
            },
            { 
                label: 'O₂ Sat (%)', 
                data: chartData.map(v => v.oxygen_saturation), 
                borderColor: '#8b5cf6', 
                tension: 0.4, 
                pointRadius: 2 
            }
        ]
    },
    options: commonOptions
});
</script>
@endsection
