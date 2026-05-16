@extends('layouts.app')
@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-800">All Appointments</h2>
        <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <i class="fas fa-plus"></i> Book Appointment
        </a>
    </div>
    <!-- Filter -->
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">Filter</button>
        <a href="{{ route('patient.appointments.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">Reset</a>
    </form>
    <div class="space-y-3">
        @forelse($appointments as $appt)
            <div class="border border-gray-100 rounded-xl p-4 hover:border-blue-200 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-600 flex-shrink-0">
                        {{ strtoupper(substr($appt->doctor->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-800">Dr. {{ $appt->doctor->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ $appt->doctor->specialization }}</span>
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full capitalize {{ $appt->status_badge }}">{{ $appt->status }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ $appt->appointment_date->format('d M Y') }}
                            <i class="fas fa-clock ml-3 mr-1"></i>{{ $appt->appointment_time }}
                        </div>
                        @if($appt->reason)<div class="text-xs text-gray-400 mt-0.5">{{ $appt->reason }}</div>@endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(in_array($appt->status, ['pending', 'approved']))
                            <form method="POST" action="{{ route('patient.appointments.destroy', $appt) }}" onsubmit="return confirm('Cancel this appointment?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-times mr-1"></i>Cancel
                                </button>
                            </form>
                        @endif
                        @if($appt->status === 'approved' && $appt->video_room_id)
                            <a href="{{ route('video-room.show', $appt) }}" class="text-xs bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                <i class="fas fa-video mr-1"></i> Join Video
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-calendar-check text-5xl mb-4 block opacity-20"></i>
                <p>No appointments found. <a href="{{ route('patient.appointments.create') }}" class="text-blue-600 hover:underline">Book one now!</a></p>
            </div>
        @endforelse
    </div>
    @if($appointments->hasPages())<div class="mt-6">{{ $appointments->withQueryString()->links() }}</div>@endif
</div>
@endsection
