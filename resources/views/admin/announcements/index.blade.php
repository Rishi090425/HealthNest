@extends('layouts.app')
@section('title', 'Announcements')
@section('page-title', 'System Announcements')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Broadcast important updates to doctors, patients, or specific branches.</p>
        <a href="{{ route('admin.announcements.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-bullhorn mr-2"></i>New Announcement
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($announcements as $ann)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border-l-4 {{ $ann->target_role === 'doctor' ? 'border-blue-500' : ($ann->target_role === 'patient' ? 'border-green-500' : 'border-primary-500') }}">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $ann->title }}</h3>
                    <div class="flex gap-3 mt-1">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium capitalize
                            {{ $ann->target_role === 'doctor' ? 'bg-blue-100 text-blue-700' : ($ann->target_role === 'patient' ? 'bg-green-100 text-green-700' : 'bg-primary-100 text-primary-700') }}">
                            To: {{ $ann->target_role }}
                        </span>
                        @if($ann->branch)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $ann->branch->name }}
                        </span>
                        @endif
                        <span class="text-xs text-gray-400 mt-0.5">
                            <i class="fas fa-clock mr-1"></i>{{ $ann->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.announcements.edit', $ann) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}" onsubmit="return confirm('Delete this announcement?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-wrap">
                {{ $ann->content }}
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-bullhorn text-4xl mb-3 text-gray-200 dark:text-gray-600"></i>
            <p>No announcements found.</p>
        </div>
        @endforelse
    </div>
    <div>{{ $announcements->links() }}</div>
</div>
@endsection
