@extends('layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')
@section('content')
<div class="space-y-5">
    {{-- Filters --}}
    <form method="GET" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 flex gap-3 flex-wrap">
        <input type="text" name="action" value="{{ request('action') }}" placeholder="Search action..."
               class="flex-1 min-w-48 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-5 py-2 rounded-lg transition-colors"><i class="fas fa-search mr-1"></i>Filter</button>
        @if(request()->hasAny(['action','user_id']))
        <a href="{{ route('admin.audit-logs.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Clear</a>
        @endif
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Time</th>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                        <th class="px-4 py-3 text-left font-medium">Model</th>
                        <th class="px-4 py-3 text-left font-medium">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                        <td class="px-4 py-3">
                            @if($log->user)
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-xs">{{ $log->user->name }}</p>
                                <p class="text-gray-400 text-xs capitalize">{{ $log->user->role }}</p>
                            </div>
                            @else
                            <span class="text-gray-400 text-xs">System</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">
                            @if($log->model_type)
                            {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            @else —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs font-mono">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No audit logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
