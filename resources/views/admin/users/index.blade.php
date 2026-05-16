@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('content')
<div class="space-y-5">
    {{-- Filter Bar --}}
    <form method="GET" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
               class="flex-1 min-w-48 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
        <select name="role" class="rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
            <option value="">All Roles</option>
            <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
            <option value="doctor"  {{ request('role') === 'doctor'  ? 'selected' : '' }}>Doctor</option>
            <option value="patient" {{ request('role') === 'patient' ? 'selected' : '' }}>Patient</option>
        </select>
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-5 py-2 rounded-lg transition-colors">
            <i class="fas fa-search mr-1"></i>Filter
        </button>
        <a href="{{ route('admin.users.create') }}" class="ml-auto bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-user-plus mr-2"></i>Add User
        </a>
    </form>

    {{-- Stats --}}
    @php
        $total   = \App\Models\User::count();
        $doctors = \App\Models\User::where('role','doctor')->count();
        $patients= \App\Models\User::where('role','patient')->count();
        $active  = \App\Models\User::where('status','active')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach([['Total Users',$total,'fa-users','blue'],['Doctors',$doctors,'fa-user-md','green'],['Patients',$patients,'fa-procedures','purple'],['Active',$active,'fa-check-circle','emerald']] as [$label,$count,$icon,$color])
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/40 rounded-lg flex items-center justify-center">
                <i class="fas {{ $icon }} text-{{ $color }}-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $label }}</p>
                <p class="font-bold text-gray-900 dark:text-white">{{ $count }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Users Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium">Role</th>
                        <th class="px-4 py-3 text-left font-medium">Phone</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Joined</th>
                        <th class="px-4 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white
                                    {{ $user->role === 'admin' ? 'bg-red-500' : ($user->role === 'doctor' ? 'bg-blue-500' : 'bg-green-500') }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : ($user->role === 'doctor' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $user->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ ($user->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-3 items-center">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Deactivate this user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                        <i class="fas fa-ban mr-1"></i>Deactivate
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $users->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
