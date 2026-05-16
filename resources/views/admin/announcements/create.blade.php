@extends('layouts.app')
@section('title', isset($announcement) ? 'Edit Announcement' : 'New Announcement')
@section('page-title', isset($announcement) ? 'Edit Announcement' : 'New Announcement')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
            @csrf
            @if(isset($announcement)) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required
                       placeholder="Enter a catchy title..."
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Role *</label>
                    <select name="target_role" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="all"     {{ old('target_role', $announcement->target_role ?? '') === 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="doctor"  {{ old('target_role', $announcement->target_role ?? '') === 'doctor' ? 'selected' : '' }}>Doctors Only</option>
                        <option value="patient" {{ old('target_role', $announcement->target_role ?? '') === 'patient' ? 'selected' : '' }}>Patients Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Branch</label>
                    <select name="target_branch_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('target_branch_id', $announcement->target_branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content *</label>
                <textarea name="content" rows="6" required placeholder="Write your message here..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('content', $announcement->content ?? '') }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-paper-plane mr-2"></i>{{ isset($announcement) ? 'Update' : 'Publish' }} Announcement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
