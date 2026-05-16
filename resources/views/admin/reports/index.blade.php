@extends('layouts.app')
@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')
@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
<div class="space-y-6">
    {{-- Period Selector --}}
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Period:</span>
        @foreach([7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 3 months'] as $days => $label)
        <a href="{{ route('admin.reports.index', ['period' => $days]) }}"
           class="px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $period == $days ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-5 text-white">
            <p class="text-primary-200 text-xs font-medium mb-1">Total Revenue</p>
            <p class="text-3xl font-bold">₹{{ number_format($totalRevenue, 2) }}</p>
            <p class="text-primary-300 text-xs mt-1">Last {{ $period }} days</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white">
            <p class="text-emerald-200 text-xs font-medium mb-1">Total Appointments</p>
            <p class="text-3xl font-bold">{{ $totalAppointments }}</p>
            <p class="text-emerald-300 text-xs mt-1">Last {{ $period }} days</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-5 text-white">
            <p class="text-yellow-100 text-xs font-medium mb-1">Avg Doctor Rating</p>
            <p class="text-3xl font-bold">{{ $averageRating ? number_format($averageRating, 1) . ' ★' : 'N/A' }}</p>
            <p class="text-yellow-200 text-xs mt-1">Last {{ $period }} days</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Daily Revenue</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        {{-- Appointment Status Doughnut --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Appointments by Status</h3>
            <div class="flex items-center justify-center">
                <canvas id="statusChart" height="200" style="max-width:300px;"></canvas>
            </div>
        </div>

        {{-- Top Doctors --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Top Doctors by Appointments</h3>
            <div class="space-y-3">
                @forelse($topDoctors as $i => $doc)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-xs font-bold text-primary-700 dark:text-primary-300">{{ $i + 1 }}</span>
                    <div class="flex-1">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $doc->user->name }}</span>
                            <span class="text-gray-500">{{ $doc->appointments_count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                            <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ $topDoctors->max('appointments_count') > 0 ? ($doc->appointments_count / $topDoctors->max('appointments_count')) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">No data available.</p>
                @endforelse
            </div>
        </div>

        {{-- New Patients Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">New Patient Registrations</h3>
            <canvas id="patientsChart" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Revenue Chart
const rd = @json($dailyRevenue);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: rd.map(r => new Date(r.date).toLocaleDateString('en-IN', {day:'2-digit',month:'short'})),
        datasets: [{ label: 'Revenue (₹)', data: rd.map(r => r.total), backgroundColor: 'rgba(59,130,246,0.7)', borderRadius: 6 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Status Doughnut
const sd = @json($appointmentStats);
const statusLabels = Object.keys(sd).map(s => s.charAt(0).toUpperCase() + s.slice(1));
const statusData   = Object.values(sd);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{ data: statusData, backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});

// Patients Chart
const pd = @json($newPatients);
new Chart(document.getElementById('patientsChart'), {
    type: 'line',
    data: {
        labels: pd.map(p => new Date(p.date).toLocaleDateString('en-IN', {day:'2-digit',month:'short'})),
        datasets: [{ label: 'New Patients', data: pd.map(p => p.count), borderColor: 'rgb(16,185,129)', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endsection
