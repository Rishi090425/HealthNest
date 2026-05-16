@extends('layouts.app')
@section('title', 'Virtual Queue')
@section('page-title', 'Walk-in Virtual Queue')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    @if($activeQueue)
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border-t-8 border-primary-600 p-8 text-center">
        <div class="mb-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Your Position</p>
            <div class="text-6xl font-black text-primary-600">#{{ $activeQueue->queue_number }}</div>
            <p class="text-sm font-medium text-gray-500 mt-2">Department: <span class="text-gray-900 dark:text-white">{{ $activeQueue->department->name }}</span></p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-slate-50 dark:bg-gray-700/50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                <p class="text-sm font-bold {{ $activeQueue->status === 'calling' ? 'text-green-600 animate-pulse' : 'text-yellow-600' }}">
                    {{ strtoupper($activeQueue->status) }}
                </p>
            </div>
            <div class="bg-slate-50 dark:bg-gray-700/50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Est. Wait</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $activeQueue->estimated_wait_time }} mins</p>
            </div>
        </div>

        @if($activeQueue->status === 'calling')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 rounded-2xl mb-8 flex items-center gap-3">
            <i class="fas fa-bullhorn text-green-600 text-xl"></i>
            <p class="text-sm text-green-800 dark:text-green-400 font-bold">It's your turn! Please proceed to the counter.</p>
        </div>
        @endif

        <div class="flex flex-col gap-3">
            <p class="text-xs text-gray-400">Keep this page open for real-time updates.</p>
            <form method="POST" action="{{ route('patient.queue.cancel', $activeQueue) }}">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-bold text-red-500 hover:underline uppercase tracking-widest">Leave Queue</button>
            </form>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users-viewfinder text-primary-600 text-2xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Join the Virtual Queue</h2>
            <p class="text-gray-500 text-sm mt-2">Skip the physical waiting room. Get in line from your phone.</p>
        </div>

        <form method="POST" action="{{ route('patient.queue.store') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 ml-1">Select Department</label>
                <div class="grid grid-cols-1 gap-3">
                    @foreach($departments as $dept)
                    <label class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/10 border-gray-100 dark:border-gray-700 hover:border-primary-200">
                        <input type="radio" name="department_id" value="{{ $dept->id }}" class="hidden" required>
                        <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-primary-600">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $dept->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $dept->description ?? 'Walk-in consultations' }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300"></i>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary-200 transition-all flex items-center justify-center gap-2 group">
                Join Queue
                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>
    </div>
    @endif
</div>

@if($activeQueue)
<script>
    // Simple polling for real-time updates since Pusher is offline
    setInterval(function() {
        window.location.reload();
    }, 15000); // Reload every 15 seconds
</script>
@endif
@endsection
