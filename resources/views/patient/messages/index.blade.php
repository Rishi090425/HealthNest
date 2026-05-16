@extends('layouts.app')
@section('title', 'Messages')
@section('page-title', 'Messages')
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" style="min-height: 480px;">
    <div class="flex h-full" style="min-height: 480px;">
        {{-- Conversation List --}}
        <div class="w-72 border-r dark:border-gray-700 flex flex-col">
            <div class="px-4 py-3 border-b dark:border-gray-700">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Conversations</p>
            </div>
            <div class="flex-1 overflow-y-auto divide-y dark:divide-gray-700">
                @forelse($conversations as $person)
                <a href="{{ isset($currentUser) && $currentUser->id === $person->id
                            ? '#'
                            : (auth()->user()->role === 'patient'
                               ? route('patient.messages.show', $person)
                               : route('doctor.messages.show', $person)) }}"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ isset($currentUser) && $currentUser->id === $person->id ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}">
                    <div class="w-9 h-9 rounded-full bg-primary-400 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        {{ strtoupper(substr($person->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $person->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ $person->role }}</p>
                    </div>
                </a>
                @empty
                <div class="px-4 py-8 text-center text-sm text-gray-400">No conversations yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Message Pane --}}
        <div class="flex-1 flex flex-col">
            @if(isset($messages) && isset($user))
            <div class="px-5 py-3 border-b dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-400 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $user->name }}</p>
            </div>
            <div id="message-box" class="flex-1 overflow-y-auto p-5 space-y-3">
                @foreach($messages as $msg)
                @php $isMine = $msg->sender_id === auth()->id(); @endphp
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2.5 rounded-2xl text-sm {{ $isMine ? 'bg-primary-600 text-white rounded-br-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-bl-sm' }}">
                        <p>{{ $msg->content }}</p>
                        <p class="text-xs mt-1 {{ $isMine ? 'text-primary-200' : 'text-gray-400' }}">
                            {{ $msg->created_at->format('h:i A') }}
                            @if($isMine && $msg->read_at) · Read @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="border-t dark:border-gray-700 p-4">
                <form method="POST"
                      action="{{ auth()->user()->role === 'patient' ? route('patient.messages.store') : route('doctor.messages.store') }}"
                      class="flex gap-2">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <input type="text" name="content" placeholder="Type a message..." required
                           class="flex-1 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            @else
            <div class="flex-1 flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <i class="fas fa-comments text-5xl mb-4 text-gray-200 dark:text-gray-600"></i>
                    <p class="text-sm">Select a conversation to start messaging</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-scroll to bottom
const box = document.getElementById('message-box');
if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection
