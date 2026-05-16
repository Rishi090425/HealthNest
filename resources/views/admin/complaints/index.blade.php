@extends('layouts.app')
@section('title', 'Complaints & Requests')
@section('page-title', 'Complaints & Requests')
@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">All Submissions</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $complaints->total() }} total submissions</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
            <option value="resolved"  {{ request('status') === 'resolved'  ? 'selected' : '' }}>Resolved</option>
        </select>
        <select name="type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Types</option>
            <option value="complaint"      {{ request('type') === 'complaint'      ? 'selected' : '' }}>Complaint</option>
            <option value="change_request" {{ request('type') === 'change_request' ? 'selected' : '' }}>Change Request</option>
            <option value="feedback"       {{ request('type') === 'feedback'       ? 'selected' : '' }}>Feedback</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">Filter</button>
        <a href="{{ route('admin.complaints.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">Reset</a>
    </form>

    <div class="space-y-4">
        @forelse($complaints as $complaint)
            <div class="border border-gray-100 rounded-xl p-5 hover:border-blue-100 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="text-sm font-bold text-gray-800">{{ $complaint->patient->user->name }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $complaint->type_label }}</span>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $complaint->status_badge }}">{{ ucfirst(str_replace('_',' ',$complaint->status)) }}</span>
                            <span class="text-xs text-gray-400 ml-auto">{{ $complaint->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">{{ $complaint->subject }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $complaint->message }}</p>
                        @if($complaint->admin_response)
                            <div class="mt-3 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg px-4 py-3">
                                <p class="text-xs font-semibold text-blue-600 mb-1"><i class="fas fa-reply mr-1"></i>Admin Response</p>
                                <p class="text-sm text-blue-800">{{ $complaint->admin_response }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Response Form --}}
                    @if($complaint->status !== 'resolved')
                        <div class="sm:w-72 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.complaints.respond', $complaint) }}" class="space-y-2">
                                @csrf @method('PATCH')
                                <textarea name="admin_response" rows="3" required placeholder="Type your response..."
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('admin_response', $complaint->admin_response) }}</textarea>
                                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="in_review" {{ $complaint->status === 'in_review' ? 'selected' : '' }}>Mark as In Review</option>
                                    <option value="resolved">Mark as Resolved</option>
                                </select>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                                    <i class="fas fa-paper-plane mr-1"></i>Send Response
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="sm:w-20 flex-shrink-0 text-center">
                            <span class="inline-flex flex-col items-center gap-1 text-green-600">
                                <i class="fas fa-check-circle text-2xl"></i>
                                <span class="text-xs font-medium">Resolved</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-inbox text-5xl mb-4 block opacity-20"></i>
                <p>No complaints or requests found.</p>
            </div>
        @endforelse
    </div>

    @if($complaints->hasPages())
        <div class="mt-6">{{ $complaints->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
