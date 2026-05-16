@extends('layouts.app')
@section('title', 'Departments')
@section('page-title', 'Departments')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Manage hospital departments and their specialties.</p>
        <a href="{{ route('admin.departments.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Department
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($departments as $dept)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hospital text-blue-600"></i>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.departments.edit', $dept) }}" class="text-yellow-600 hover:text-yellow-800 text-xs"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" onsubmit="return confirm('Delete this department?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $dept->name }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $dept->description ?? 'No description.' }}</p>
            <div class="flex gap-4 mt-3 pt-3 border-t dark:border-gray-700 text-xs text-gray-500">
                <span><i class="fas fa-stethoscope mr-1"></i>{{ $dept->specialties_count }} Specialties</span>
                <span><i class="fas fa-user-md mr-1"></i>{{ $dept->doctors_count }} Doctors</span>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-hospital text-4xl mb-3 text-gray-200 dark:text-gray-600"></i>
            <p>No departments created yet.</p>
        </div>
        @endforelse
    </div>
    <div>{{ $departments->links() }}</div>
</div>
@endsection
