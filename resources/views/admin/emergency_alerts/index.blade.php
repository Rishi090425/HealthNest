@extends('layouts.app')
@section('title', 'Emergency SOS Alerts')
@section('page-title', 'Active SOS Alerts')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center bg-red-50 dark:bg-red-900/20 p-4 rounded-2xl border border-red-200 dark:border-red-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center animate-pulse shadow-lg shadow-red-500/50">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-red-900 dark:text-red-400 uppercase tracking-wider">Live Emergency Monitor</h2>
                <p class="text-xs text-red-700 dark:text-red-500">Real-time alerts from patients requesting immediate assistance.</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest">System Status</p>
            <p class="text-xs font-bold text-green-600 flex items-center justify-end gap-1"><span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span> Online</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($alerts as $alert)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border {{ $alert->status === 'pending' ? 'border-red-500 ring-2 ring-red-100 dark:ring-red-900/30' : 'border-gray-200 dark:border-gray-700' }} p-5 transition-all">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center font-bold text-lg text-gray-500">
                        {{ strtoupper(substr($alert->patient->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $alert->patient->user->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $alert->patient->user->phone ?? 'No phone listed' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $alert->status === 'pending' ? 'bg-red-100 text-red-700' : ($alert->status === 'responding' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ $alert->status }}
                            </span>
                            <span class="text-[10px] text-gray-400">Triggered {{ $alert->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:items-end justify-between gap-3">
                    <div class="flex gap-2">
                        <a href="https://www.google.com/maps?q={{ $alert->latitude }},{{ $alert->longitude }}" target="_blank" 
                           class="bg-blue-50 text-blue-700 hover:bg-blue-100 px-4 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-2">
                            <i class="fas fa-map-marked-alt"></i> View Location
                        </a>
                        @if($alert->status === 'pending')
                        <form method="POST" action="{{ route('admin.emergency-alerts.respond', $alert) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-yellow-200 transition-colors">
                                Mark Responding
                            </button>
                        </form>
                        @elseif($alert->status === 'responding')
                        <form method="POST" action="{{ route('admin.emergency-alerts.resolve', $alert) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-green-200 transition-colors">
                                Resolve Alert
                            </button>
                        </form>
                        @endif
                    </div>
                    @if($alert->responder)
                    <p class="text-[10px] text-gray-400">Responder: <span class="font-bold">{{ $alert->responder->name }}</span></p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center text-gray-400 border border-dashed border-gray-200 dark:border-gray-700">
            <i class="fas fa-check-circle text-4xl mb-3 text-gray-200 dark:text-gray-600"></i>
            <p>No active emergency alerts. All clear!</p>
        </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $alerts->links() }}</div>
</div>
@endsection
