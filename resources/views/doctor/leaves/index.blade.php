@extends('layouts.app')
@section('title', 'My Leaves')
@section('page-title', 'Staff Leave Management')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Request Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border-t-4 border-indigo-500">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Request Leave</h3>
            <form method="POST" action="{{ route('doctor.leaves.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">End Date</label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Reason</label>
                    <textarea name="reason" rows="3" required placeholder="Brief explanation..." class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm"></textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-200">
                    Submit Request
                </button>
            </form>
        </div>
    </div>

    {{-- History List --}}
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="font-bold text-gray-900 dark:text-white">Request History</h3>
            </div>
            <div class="divide-y dark:divide-gray-700">
                @forelse($leaves as $leave)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $leave->reason }}</p>
                            @if($leave->admin_notes)
                            <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Admin Response:</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $leave->admin_notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $leave->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($leave->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                {{ $leave->status }}
                            </span>
                            @if($leave->status === 'pending')
                            <form method="POST" action="{{ route('doctor.leaves.destroy', $leave) }}" class="mt-2">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] font-bold text-red-400 hover:text-red-600 uppercase tracking-widest">Cancel</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-400">No leave requests found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
