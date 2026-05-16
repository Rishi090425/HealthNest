@extends('layouts.app')
@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('content')

{{-- Status Tab Bar --}}
<div class="flex flex-wrap gap-2 mb-5">
    @foreach([
        ['label'=>'All',       'value'=>'',          'count'=>$counts['all'],       'color'=>'gray'],
        ['label'=>'Pending',   'value'=>'pending',   'count'=>$counts['pending'],   'color'=>'yellow'],
        ['label'=>'Approved',  'value'=>'approved',  'count'=>$counts['approved'],  'color'=>'blue'],
        ['label'=>'Completed', 'value'=>'completed', 'count'=>$counts['completed'], 'color'=>'green'],
        ['label'=>'Cancelled', 'value'=>'cancelled', 'count'=>$counts['cancelled'], 'color'=>'red'],
    ] as $tab)
        @php $active = request('status', '') === $tab['value']; @endphp
        <a href="{{ route('doctor.appointments.index', array_merge(request()->except('status','page'), $tab['value'] ? ['status'=>$tab['value']] : [])) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium border transition-all
            {{ $active
                ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300 hover:text-blue-600' }}">
            {{ $tab['label'] }}
            <span class="text-xs px-1.5 py-0.5 rounded-full {{ $active ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' }}">
                {{ $tab['count'] }}
            </span>
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    {{-- Date filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="relative">
            <input type="date" name="date" value="{{ request('date') }}"
                class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-blue-700 transition-colors">Filter</button>
        <a href="{{ route('doctor.appointments.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">Reset</a>
    </form>

    <div class="space-y-3">
        @forelse($appointments as $appt)
            <div class="border border-gray-100 rounded-xl p-4 hover:border-blue-200 hover:bg-blue-50/20 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-600 flex-shrink-0">
                        {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-800">{{ $appt->patient->user->name }}</span>
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full capitalize {{ $appt->status_badge }}">
                                {{ $appt->status }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1 text-blue-400"></i>{{ $appt->appointment_date->format('d M Y') }}
                            <i class="fas fa-clock ml-3 mr-1 text-gray-300"></i>{{ $appt->appointment_time }}
                        </div>
                        @if($appt->reason)
                            <div class="text-xs text-gray-400 mt-1 truncate">{{ $appt->reason }}</div>
                        @endif
                    </div>

                    {{-- ── Doctor Action Buttons ── --}}
                    <div class="flex items-center gap-2 flex-shrink-0">

                        @if($appt->status === 'pending')
                            {{-- Approve --}}
                            <form method="POST" action="{{ route('doctor.appointments.approve', $appt) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            {{-- Reject --}}
                            <form method="POST" action="{{ route('doctor.appointments.reject', $appt) }}"
                                  onsubmit="return confirm('Cancel this appointment?')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-red-200 transition-colors">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>

                        @elseif($appt->status === 'approved')
                            {{-- Mark Complete — doctor's exclusive action --}}
                            <form method="POST" action="{{ route('doctor.appointments.complete', $appt) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    title="Mark this appointment as completed"
                                    class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                    <i class="fas fa-check-double"></i> Mark Complete
                                </button>
                            </form>
                            {{-- Add Consultation Notes --}}
                            <a href="{{ route('doctor.consultations.create', $appt) }}"
                                class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">
                                <i class="fas fa-notes-medical"></i> Add Notes
                            </a>

                            {{-- Video Consultation --}}
                            @if(!$appt->video_room_id)
                                <form method="POST" action="{{ route('video-room.generate', $appt) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                        <i class="fas fa-video"></i> Start Video
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('video-room.show', $appt) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                    <i class="fas fa-video"></i> Join Video
                                </a>
                            @endif

                        @elseif($appt->status === 'completed')
                            {{-- View consultation notes --}}
                            <a href="{{ route('doctor.consultations.show', $appt) }}"
                                class="inline-flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 transition-colors">
                                <i class="fas fa-eye"></i> View Notes
                            </a>
                            @if(!$appt->consultation)
                                {{-- Completed but no notes yet --}}
                                <a href="{{ route('doctor.consultations.create', $appt) }}"
                                    class="inline-flex items-center gap-1.5 bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-medium px-3 py-1.5 rounded-lg border border-orange-200 transition-colors">
                                    <i class="fas fa-pen"></i> Add Notes
                                </a>
                            @endif

                        @else {{-- cancelled --}}
                            <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                <i class="fas fa-ban mr-1"></i>Cancelled
                            </span>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-calendar-check text-5xl mb-4 block opacity-20"></i>
                <p>No appointments found.</p>
            </div>
        @endforelse
    </div>

    @if($appointments->hasPages())
        <div class="mt-6">{{ $appointments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
