@extends('layouts.app')
@section('title', 'Manage Patients')
@section('page-title', 'Manage Patients')
@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">All Patients</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $patients->total() }} patients registered</p>
        </div>
    </div>
    <form method="GET" class="mb-5">
        <div class="relative max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patients..."
                class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </form>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Patient</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 hidden sm:table-cell">Gender</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 hidden md:table-cell">Blood</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 hidden lg:table-cell">Phone</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 pr-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-sm font-bold text-purple-600 flex-shrink-0">
                                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ $patient->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $patient->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 pr-4 hidden sm:table-cell"><span class="text-sm text-gray-600 capitalize">{{ $patient->gender ?? '—' }}</span></td>
                        <td class="py-4 pr-4 hidden md:table-cell">
                            @if($patient->blood_group)
                                <span class="text-xs font-bold bg-red-50 text-red-600 px-2 py-1 rounded-full">{{ $patient->blood_group }}</span>
                            @else <span class="text-gray-400 text-sm">—</span> @endif
                        </td>
                        <td class="py-4 pr-4 hidden lg:table-cell"><span class="text-sm text-gray-600">{{ $patient->user->phone ?? '—' }}</span></td>
                        <td class="py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.patients.show', $patient) }}" class="text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors"><i class="fas fa-eye"></i></a>
                                <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-16 text-center text-gray-400">
                        <i class="fas fa-users text-4xl mb-3 block opacity-30"></i>No patients found.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())<div class="mt-6">{{ $patients->withQueryString()->links() }}</div>@endif
</div>
@endsection
