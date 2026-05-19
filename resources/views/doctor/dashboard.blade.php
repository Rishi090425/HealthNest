@extends('layouts.app')
@section('title', 'Doctor Dashboard')
@section('page-title', 'Doctor Overview')
@section('content')
<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Cases -->
        <a href="{{ route('doctor.appointments.index') }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border-b-4 border-blue-500 block hover:shadow-md hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Cases</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>
        </a>

        <!-- Pending -->
        <a href="{{ route('doctor.appointments.index') }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border-b-4 border-yellow-500 block hover:shadow-md hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-clock text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Pending</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_appointments'] }}</p>
                </div>
            </div>
        </a>

        <!-- Today -->
        <a href="{{ route('doctor.appointments.index') }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border-b-4 border-green-500 block hover:shadow-md hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-green-100 dark:bg-green-900/40 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Today</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['today_appointments'] }}</p>
                </div>
            </div>
        </a>

        <!-- Completed -->
        <a href="{{ route('doctor.appointments.index') }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border-b-4 border-primary-500 block hover:shadow-md hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-primary-100 dark:bg-primary-900/40 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-primary-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Completed</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upcoming Appointments --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-100">Confirmed Upcoming</h3>
                <a href="{{ route('doctor.appointments.index') }}" class="text-xs text-primary-600 hover:underline">View Schedule</a>
            </div>
            <div class="p-6 space-y-4">
                @forelse($upcoming as $appt)
                <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <a href="{{ route('doctor.appointments.index') }}" class="text-center bg-white dark:bg-gray-800 p-2 rounded-lg min-w-[50px] shadow-sm block hover:scale-105 transition-transform shrink-0">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">{{ $appt->appointment_date->format('M') }}</p>
                        <p class="text-sm font-bold text-primary-600">{{ $appt->appointment_date->format('d') }}</p>
                    </a>
                    <a href="{{ route('doctor.appointments.index') }}" class="flex-1 min-w-0 group block">
                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 group-hover:underline">{{ $appt->patient->user->name }}</p>
                        <p class="text-[10px] text-gray-500">{{ $appt->appointment_time }} · {{ $appt->reason ?? 'Checkup' }}</p>
                    </a>
                    <a href="{{ route('doctor.consultations.create', $appt) }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg shadow-sm hover:shadow transition-all shrink-0">Consult</a>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No upcoming appointments.</p>
                @endforelse
            </div>
        </div>

        {{-- Pending Requests --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-100">Pending Requests</h3>
                <a href="{{ route('doctor.appointments.index') }}" class="text-xs text-primary-600 hover:underline">Manage All</a>
            </div>
            <div class="p-6 space-y-4">
                @forelse($pending as $appt)
                <div class="flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 p-2 rounded-xl transition-colors">
                    <a href="{{ route('doctor.appointments.index') }}" class="flex items-center gap-4 flex-1 group min-w-0">
                        <div class="w-9 h-9 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 font-bold text-xs shrink-0">
                            {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-blue-600 group-hover:underline">{{ $appt->patient->user->name }}</p>
                            <p class="text-[10px] text-gray-500">Requested: {{ $appt->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    <div class="flex gap-1 shrink-0">
                        <form method="POST" action="{{ route('doctor.appointments.approve', $appt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-7 h-7 bg-green-500 text-white rounded-lg flex items-center justify-center text-xs hover:bg-green-600 transition-colors shadow-sm"><i class="fas fa-check"></i></button>
                        </form>
                        <form method="POST" action="{{ route('doctor.appointments.reject', $appt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-7 h-7 bg-red-500 text-white rounded-lg flex items-center justify-center text-xs hover:bg-red-600 transition-colors shadow-sm"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No pending requests.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
