@extends('layouts.app')
@section('title', 'Manage Doctors')
@section('page-title', 'Manage Doctors')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">All Doctors</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $doctors->total() }} doctors registered</p>
        </div>
        <a href="{{ route('admin.doctors.create') }}" id="add-doctor-btn"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <i class="fas fa-plus"></i> Add Doctor
        </a>
    </div>

    <!-- Search -->
    <form method="GET" class="mb-5">
        <div class="relative max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search doctors..."
                class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Doctor</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 hidden sm:table-cell">Specialization</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 hidden md:table-cell">Experience</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 hidden lg:table-cell">Fee</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($doctors as $doctor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 pr-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600 flex-shrink-0">
                                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ $doctor->user->display_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $doctor->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 pr-4 hidden sm:table-cell">
                            <span class="text-sm text-gray-600">{{ $doctor->specialization }}</span>
                        </td>
                        <td class="py-4 pr-4 hidden md:table-cell">
                            <span class="text-sm text-gray-600">{{ $doctor->experience_years }} yrs</span>
                        </td>
                        <td class="py-4 pr-4 hidden lg:table-cell">
                            <span class="text-sm text-gray-600">₹{{ number_format($doctor->consultation_fee) }}</span>
                        </td>
                        <td class="py-4 pr-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $doctor->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($doctor->status) }}
                            </span>
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.doctors.edit', $doctor) }}"
                                    class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" onsubmit="return confirm('Delete this doctor?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-gray-400">
                            <i class="fas fa-user-md text-4xl mb-3 block opacity-30"></i>
                            No doctors found. <a href="{{ route('admin.doctors.create') }}" class="text-blue-600 hover:underline">Add one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($doctors->hasPages())
        <div class="mt-6">{{ $doctors->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
