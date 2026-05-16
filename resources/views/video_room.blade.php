@extends('layouts.app')
@section('title', 'Video Consultation')
@section('page-title', 'Virtual Consult: ' . $appointment->patient->user->name)
@section('content')
<div class="h-[calc(100vh-180px)] flex flex-col lg:flex-row gap-6">
    {{-- Main Video Area --}}
    <div class="flex-1 bg-gray-900 rounded-3xl overflow-hidden relative group">
        {{-- Remote Video (Large) --}}
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 animate-pulse">
                    <i class="fas fa-video-slash text-gray-500 text-2xl"></i>
                </div>
                <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Waiting for {{ auth()->user()->isDoctor() ? 'Patient' : 'Doctor' }}...</p>
            </div>
        </div>

        {{-- Local Video (Small Overlay) --}}
        <div class="absolute bottom-6 right-6 w-48 h-32 bg-gray-800 rounded-2xl border-2 border-white/10 overflow-hidden shadow-2xl">
            <div class="h-full flex items-center justify-center">
                <i class="fas fa-user text-gray-600"></i>
            </div>
            <div class="absolute bottom-2 left-2 bg-black/40 px-2 py-0.5 rounded-md">
                <p class="text-[10px] text-white font-bold">You (Camera Off)</p>
            </div>
        </div>

        {{-- Controls Bar (Auto-hide) --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-4 bg-black/40 backdrop-blur-xl p-3 rounded-2xl border border-white/10 opacity-0 group-hover:opacity-100 transition-all duration-300">
            <button class="w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all"><i class="fas fa-microphone-slash"></i></button>
            <button class="w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all"><i class="fas fa-video-slash"></i></button>
            <button class="w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all"><i class="fas fa-share-square"></i></button>
            <a href="{{ url()->previous() }}" class="w-12 h-12 bg-red-500 hover:bg-red-600 text-white rounded-xl flex items-center justify-center transition-all shadow-lg shadow-red-500/20"><i class="fas fa-phone-slash"></i></a>
        </div>

        {{-- Top Info --}}
        <div class="absolute top-6 left-6 flex items-center gap-3">
            <div class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase animate-pulse">Live</div>
            <div class="bg-black/40 backdrop-blur-md px-3 py-1 rounded-md text-white text-[10px] font-bold uppercase tracking-widest border border-white/10">
                Room ID: {{ strtoupper(substr($appointment->video_room_id, 0, 8)) }}
            </div>
        </div>
    </div>

    {{-- Chat & Info Sidebar --}}
    <div class="w-full lg:w-80 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 h-full flex flex-col">
            <div class="mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center text-primary-600">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Consultation Info</p>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $appointment->patient->user->name }}</h3>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 mb-6 pr-2 custom-scrollbar">
                <div class="bg-slate-50 dark:bg-gray-700/50 p-4 rounded-2xl">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Reason for Visit</p>
                    <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">{{ $appointment->reason }}</p>
                </div>
                {{-- Quick Chat Placeholder --}}
                <div class="space-y-3 mt-8">
                    <div class="flex flex-col items-end">
                        <div class="bg-primary-600 text-white p-3 rounded-2xl rounded-tr-none text-xs max-w-[80%]">
                            Hello, I'm ready for the call.
                        </div>
                        <span class="text-[8px] text-gray-400 mt-1 uppercase">10:05 AM</span>
                    </div>
                </div>
            </div>

            <div class="mt-auto">
                <div class="relative">
                    <input type="text" placeholder="Type a message..." 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-none rounded-2xl text-xs focus:ring-2 focus:ring-primary-500 transition-all">
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-600"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection
