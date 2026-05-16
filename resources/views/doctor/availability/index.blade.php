@extends('layouts.app')
@section('title', 'My Availability')
@section('page-title', 'Manage Availability')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Weekly Schedule</h2>
            <p class="text-sm text-gray-500 mt-1">Set your available days and working hours</p>
        </div>
        <form method="POST" action="{{ route('doctor.availability.update') }}" class="space-y-4">
            @csrf
            @foreach($days as $day)
                @php $slot = $availability[$day] ?? null; @endphp
                <div class="border border-gray-100 rounded-xl p-4 transition-all hover:border-blue-200">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex items-center gap-3 min-w-40">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="available[{{ $day }}]" id="avail_{{ $day }}"
                                    {{ $slot && $slot->is_available ? 'checked' : '' }}
                                    class="sr-only peer" onchange="toggleDay('{{ $day }}')">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <span class="text-sm font-medium text-gray-700">{{ $day }}</span>
                        </div>
                        <div id="times_{{ $day }}" class="flex items-center gap-3 {{ $slot && $slot->is_available ? '' : 'opacity-40 pointer-events-none' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">From</span>
                                <input type="time" name="start_time[{{ $day }}]" value="{{ $slot?->start_time ?? '09:00' }}"
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">To</span>
                                <input type="time" name="end_time[{{ $day }}]" value="{{ $slot?->end_time ?? '17:00' }}"
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        @if(!$slot || !$slot->is_available)
                            <span id="closed_{{ $day }}" class="text-xs text-gray-400 italic">Not Available</span>
                        @else
                            <span id="closed_{{ $day }}" class="text-xs text-gray-400 italic hidden">Not Available</span>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="pt-4">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-lg text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Schedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function toggleDay(day) {
    const cb = document.getElementById('avail_' + day);
    const times = document.getElementById('times_' + day);
    const closed = document.getElementById('closed_' + day);
    if (cb.checked) {
        times.classList.remove('opacity-40', 'pointer-events-none');
        closed.classList.add('hidden');
    } else {
        times.classList.add('opacity-40', 'pointer-events-none');
        closed.classList.remove('hidden');
    }
}
</script>
@endsection
