@extends('layouts.app')
@section('title', 'Leave Requests')
@section('page-title', 'Staff Leave Review')
@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 text-left">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Doctor</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Dates</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Reason</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Status</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($leaves as $leave)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900 dark:text-white">{{ $leave->doctor->user->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $leave->doctor->specialty->name ?? 'Doctor' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $leave->start_date->diffInDays($leave->end_date) + 1 }} days</p>
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate text-gray-500 dark:text-gray-400" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $leave->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($leave->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($leave->status === 'pending')
                            <button onclick="openLeaveModal({{ $leave->id }}, '{{ $leave->doctor->user->name }}')" class="bg-gray-900 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-black transition-colors">REVIEW</button>
                            @else
                            <span class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">PROCESSED</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No leave requests to show.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div>{{ $leaves->links() }}</div>
</div>

{{-- Review Modal --}}
<div id="leaveModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-95 opacity-0 duration-200" id="modalContent">
        <div class="p-6 border-b dark:border-gray-700">
            <h3 class="font-bold text-gray-900 dark:text-white" id="modalTitle">Review Leave</h3>
        </div>
        <form method="POST" id="leaveForm" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Decision</label>
                <select name="status" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm font-bold">
                    <option value="approved">APPROVE</option>
                    <option value="rejected">REJECT</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Admin Notes</label>
                <textarea name="admin_notes" rows="3" placeholder="Optional response to doctor..." class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm"></textarea>
            </div>
            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeLeaveModal()" class="flex-1 py-3 text-sm font-bold text-gray-400 hover:text-gray-600">Close</button>
                <button type="submit" class="flex-[2] bg-gray-900 text-white font-bold py-3 rounded-2xl shadow-lg">Confirm Action</button>
            </div>
        </form>
    </div>
</div>

<script>
function openLeaveModal(id, name) {
    const modal = document.getElementById('leaveModal');
    const content = document.getElementById('modalContent');
    const form = document.getElementById('leaveForm');
    document.getElementById('modalTitle').innerText = 'Review: ' + name;
    form.action = '/admin/leaves/' + id;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeLeaveModal() {
    const modal = document.getElementById('leaveModal');
    const content = document.getElementById('modalContent');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}
</script>
@endsection
