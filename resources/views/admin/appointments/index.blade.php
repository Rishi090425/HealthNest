@extends('layouts.app')
@section('title', 'Manage Appointments')
@section('page-title', 'Manage Appointments')
@section('content')

{{-- Status Tab Bar --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach([
        ['label'=>'All',       'value'=>'',          'count'=>$counts['all'],       'color'=>'gray'],
        ['label'=>'Pending',   'value'=>'pending',   'count'=>$counts['pending'],   'color'=>'yellow'],
        ['label'=>'Approved',  'value'=>'approved',  'count'=>$counts['approved'],  'color'=>'blue'],
        ['label'=>'Completed', 'value'=>'completed', 'count'=>$counts['completed'], 'color'=>'green'],
        ['label'=>'Cancelled', 'value'=>'cancelled', 'count'=>$counts['cancelled'], 'color'=>'red'],
    ] as $tab)
        @php $active = request('status', '') === $tab['value']; @endphp
        <a href="{{ route('admin.appointments.index', array_merge(request()->except('status','page'), $tab['value'] ? ['status'=>$tab['value']] : [])) }}"
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

{{-- Info banner explaining role boundaries --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-5 text-sm">
    <i class="fas fa-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
    <p class="text-blue-700">
        <strong>Admin role:</strong> You can <span class="font-semibold text-green-700">Approve</span> or <span class="font-semibold text-red-700">Reject</span> appointments.
        Marking an appointment as <span class="font-semibold">Completed</span> is done by the <strong>assigned doctor</strong> after the consultation.
    </p>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    {{-- Search --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="relative flex-1 min-w-48">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by patient or doctor name..."
                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Search</button>
        <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">Reset</a>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 pr-4">Patient</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 pr-4 hidden sm:table-cell">Doctor</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 pr-4 hidden md:table-cell">Date & Time</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Admin Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($appointments as $appt)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 pr-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600 flex-shrink-0">
                                    {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-800 truncate">{{ $appt->patient->user->name }}</div>
                                    @if($appt->reason)
                                        <div class="text-xs text-gray-400 truncate max-w-xs hidden sm:block">{{ Str::limit($appt->reason, 35) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 pr-4 hidden sm:table-cell">
                            <div class="text-sm font-medium text-gray-700">{{ $appt->doctor->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $appt->doctor->specialization }}</div>
                        </td>
                        <td class="py-4 pr-4 hidden md:table-cell">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-calendar text-blue-400 text-xs"></i>
                                <span class="text-sm text-gray-700">{{ $appt->appointment_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <i class="fas fa-clock text-gray-300 text-xs"></i>
                                <span class="text-xs text-gray-400">{{ $appt->appointment_time }}</span>
                            </div>
                        </td>
                        <td class="py-4 pr-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full capitalize {{ $appt->status_badge }}">
                                @if($appt->status === 'pending')
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse inline-block"></span>
                                @elseif($appt->status === 'approved')
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                @elseif($appt->status === 'completed')
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                                @endif
                                {{ $appt->status }}
                            </span>
                        </td>

                        {{-- ── Admin-only Actions: Approve / Reject only ── --}}
                        <td class="py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">

                                @if($appt->status === 'pending')
                                    {{-- Approve --}}
                                    <form method="POST" action="{{ route('admin.appointments.approve', $appt) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    {{-- Reject --}}
                                    <form method="POST" action="{{ route('admin.appointments.reject', $appt) }}"
                                          onsubmit="return confirm('Reject this appointment?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-red-200 transition-colors">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>

                                @elseif($appt->status === 'approved')
                                    {{-- WhatsApp Reminder --}}
                                    <form method="POST" action="{{ route('admin.appointments.whatsapp', $appt) }}">
                                        @csrf
                                        <button type="submit" title="Send WhatsApp Reminder"
                                            class="inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 text-green-600 text-xs font-medium px-2.5 py-1.5 rounded-lg border border-green-200 transition-colors">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                    </form>

                                    {{-- Admin cannot complete — only cancel if needed --}}
                                    <span class="text-xs text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg font-medium">
                                        <i class="fas fa-user-md mr-1"></i>Awaiting Doctor
                                    </span>
                                    <form method="POST" action="{{ route('admin.appointments.reject', $appt) }}"
                                          onsubmit="return confirm('Cancel this approved appointment?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-gray-50 hover:bg-gray-100 text-gray-500 text-xs font-medium px-2.5 py-1.5 rounded-lg border border-gray-200 transition-colors">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>

                                @elseif($appt->status === 'completed')
                                    {{-- Completed by doctor — admin sees read-only --}}
                                    <span class="inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-50 px-3 py-1.5 rounded-lg font-medium border border-green-100">
                                        <i class="fas fa-check-circle"></i> Completed by Doctor
                                    </span>

                                @else {{-- cancelled --}}
                                    <form method="POST" action="{{ route('admin.appointments.approve', $appt) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-redo"></i> Re-open
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <i class="fas fa-calendar-check text-5xl text-gray-200 block mb-3"></i>
                            <p class="text-gray-400 text-sm">No appointments found for the selected filter.</p>
                            <a href="{{ route('admin.appointments.index') }}" class="text-blue-600 text-xs hover:underline mt-1 inline-block">Clear filter</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($appointments->hasPages())
        <div class="mt-6">{{ $appointments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
