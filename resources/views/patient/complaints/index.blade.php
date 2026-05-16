@extends('layouts.app')
@section('title', 'My Requests & Complaints')
@section('page-title', 'My Requests & Complaints')
@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">My Submissions</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $complaints->total() }} total submissions</p>
        </div>
        <a href="{{ route('patient.complaints.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <i class="fas fa-plus"></i> New Request
        </a>
    </div>

    <div class="space-y-4">
        @forelse($complaints as $complaint)
            <div class="border border-gray-100 rounded-xl p-5 hover:border-blue-100 transition-all">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold text-gray-800">{{ $complaint->subject }}</span>
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $complaint->type_label }}</span>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $complaint->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">{{ $complaint->message }}</p>
                <p class="text-xs text-gray-300">Submitted {{ $complaint->created_at->diffForHumans() }}</p>

                @if($complaint->admin_response)
                    <div class="mt-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                        <p class="text-xs font-semibold text-blue-600 mb-1">
                            <i class="fas fa-headset mr-1"></i>Support Response
                        </p>
                        <p class="text-sm text-blue-800">{{ $complaint->admin_response }}</p>
                        @if($complaint->resolved_at)
                            <p class="text-xs text-green-600 mt-1"><i class="fas fa-check-circle mr-1"></i>Resolved on {{ $complaint->resolved_at->format('d M Y') }}</p>
                        @endif
                    </div>
                @else
                    <div class="mt-3 bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-2.5 flex items-center gap-2">
                        <i class="fas fa-hourglass-half text-yellow-500 text-sm animate-pulse"></i>
                        <p class="text-xs text-yellow-700">Awaiting response — we typically reply within 24–48 hours.</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-comments text-5xl mb-4 block opacity-20"></i>
                <p class="mb-2">No submissions yet.</p>
                <a href="{{ route('patient.complaints.create') }}" class="text-blue-600 text-sm hover:underline">Submit your first request</a>
            </div>
        @endforelse
    </div>

    @if($complaints->hasPages())
        <div class="mt-6">{{ $complaints->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
