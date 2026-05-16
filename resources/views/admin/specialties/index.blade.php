@extends('layouts.app')
@section('title', 'Specialties')
@section('page-title', 'Specialties')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Manage clinical specialties under each department.</p>
        <a href="{{ route('admin.specialties.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Specialty
        </a>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Specialty</th>
                    <th class="px-4 py-3 text-left font-medium">Department</th>
                    <th class="px-4 py-3 text-left font-medium">Doctors</th>
                    <th class="px-4 py-3 text-left font-medium">Description</th>
                    <th class="px-4 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($specialties as $spec)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $spec->name }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $spec->department->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $spec->doctors_count }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $spec->description ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.specialties.edit', $spec) }}" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium"><i class="fas fa-edit mr-1"></i>Edit</a>
                            <form method="POST" action="{{ route('admin.specialties.destroy', $spec) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No specialties yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $specialties->links() }}</div>
    </div>
</div>
@endsection
