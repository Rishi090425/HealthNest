@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Dashboard')
@section('content')
<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 flex items-center gap-4 border-b-4 border-blue-500">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-procedures text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Patients</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_patients'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 flex items-center gap-4 border-b-4 border-green-500">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-md text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Doctors</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_doctors'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 flex items-center gap-4 border-b-4 border-purple-500">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Appointments</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_appointments'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 flex items-center gap-4 border-b-4 border-yellow-500">
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Pending Appts</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_appointments'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Appointments --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-100">Recent Appointments</h3>
                <a href="{{ route('admin.appointments.index') }}" class="text-xs text-primary-600 hover:underline font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium uppercase text-[10px] tracking-wider">Patient</th>
                            <th class="px-4 py-3 font-medium uppercase text-[10px] tracking-wider">Doctor</th>
                            <th class="px-4 py-3 font-medium uppercase text-[10px] tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($recent_appointments as $appt)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $appt->patient->user->name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $appt->appointment_date->format('d M, Y') }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">
                                Dr. {{ $appt->doctor->user->name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                       ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $appt->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Patients --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-100">New Patient Registrations</h3>
                <a href="{{ route('admin.patients.index') }}" class="text-xs text-primary-600 hover:underline font-medium">View All</a>
            </div>
            <div class="p-6 space-y-4">
                @foreach($recent_patients as $p)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-sm">
                        {{ strtoupper(substr($p->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $p->user->name }}</p>
                        <p class="text-[10px] text-gray-500">{{ $p->user->email }}</p>
                    </div>
                    <p class="text-[10px] text-gray-400">{{ $p->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
