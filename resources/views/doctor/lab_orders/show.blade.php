@extends('layouts.app')
@section('title', 'Lab Order — ' . $labOrder->test_type)
@section('page-title', 'Lab Order Detail')
@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white text-lg">{{ $labOrder->test_type }}</h2>
                <p class="text-sm text-gray-500">Patient: {{ $labOrder->patient->user->name }} · {{ $labOrder->order_date->format('d M Y') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $labOrder->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($labOrder->status) }}
            </span>
        </div>
        @if($labOrder->notes)
        <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-xl px-4 py-2">{{ $labOrder->notes }}</p>
        @endif
    </div>

    {{-- Existing Results --}}
    @if($labOrder->results->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Results</h3>
        @foreach($labOrder->results as $result)
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-3">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div><p class="text-xs text-gray-400 mb-1">Result Value</p><p class="font-semibold text-gray-900 dark:text-white">{{ $result->result_value }}</p></div>
                <div><p class="text-xs text-gray-400 mb-1">Normal Range</p><p class="font-medium">{{ $result->normal_range ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400 mb-1">Flag</p>
                    @if($result->flag)
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $result->flag === 'normal' ? 'bg-green-100 text-green-700' : ($result->flag === 'critical' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700') }}">
                        {{ ucfirst($result->flag) }}
                    </span>
                    @else <p>—</p> @endif
                </div>
                <div><p class="text-xs text-gray-400 mb-1">Date</p><p class="font-medium">{{ $result->result_date->format('d M Y') }}</p></div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Annotate Form --}}
    @if($labOrder->status === 'pending')
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Record Result</h3>
        <form method="POST" action="{{ route('doctor.lab-orders.annotate', $labOrder) }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Result Date *</label>
                    <input type="date" name="result_date" value="{{ date('Y-m-d') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Flag</label>
                    <select name="flag" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">None</option>
                        <option value="normal">Normal</option>
                        <option value="low">Low</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Result Value *</label>
                <textarea name="result_value" rows="2" required placeholder="Enter result value or description..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Normal Range</label>
                    <input type="text" name="normal_range" placeholder="e.g. 70-100 mg/dL"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Upload Report (PDF/Image)</label>
                    <input type="file" name="report_path" accept=".pdf,.jpg,.png"
                           class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>
            </div>
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                <i class="fas fa-check-circle mr-2"></i>Save Result & Mark Complete
            </button>
        </form>
    </div>
    @endif

    <a href="{{ route('doctor.lab-orders.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
        <i class="fas fa-arrow-left mr-1"></i>Back to Lab Orders
    </a>
</div>
@endsection
