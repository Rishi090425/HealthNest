@extends('layouts.app')
@section('title', isset($specialty) ? 'Edit Specialty' : 'New Specialty')
@section('page-title', isset($specialty) ? 'Edit Specialty' : 'New Specialty')
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ isset($specialty) ? route('admin.specialties.update', $specialty) : route('admin.specialties.store') }}">
            @csrf
            @if(isset($specialty)) @method('PUT') @endif
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Specialty Name *</label>
                <input type="text" name="name" value="{{ old('name', $specialty->name ?? '') }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department *</label>
                <select name="department_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">Select Department...</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id', $specialty->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
                @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('description', $specialty->description ?? '') }}</textarea>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.specialties.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    {{ isset($specialty) ? 'Update' : 'Create' }} Specialty
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
