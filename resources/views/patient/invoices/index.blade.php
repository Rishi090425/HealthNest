@extends('layouts.app')
@section('title', 'Bills & Payments')
@section('page-title', 'Bills & Payments')
@section('content')
<div class="space-y-6">
    {{-- Summary Cards --}}
    @php
        $totalDue = $invoices->where('status','!=','paid')->sum('amount');
        $totalPaid = $invoices->sum(fn($i) => $i->total_paid);
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Invoices</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $invoices->total() }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Amount Due</p>
                <p class="text-2xl font-bold text-red-600">₹{{ number_format($totalDue, 2) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Paid</p>
                <p class="text-2xl font-bold text-green-600">₹{{ number_format($totalPaid, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Invoice List --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Invoice History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Invoice #</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-left font-medium">Doctor</th>
                        <th class="px-4 py-3 text-left font-medium">Amount</th>
                        <th class="px-4 py-3 text-left font-medium">Paid</th>
                        <th class="px-4 py-3 text-left font-medium">Due</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 font-mono text-gray-700 dark:text-gray-300">#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $invoice->issued_date?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">Dr. {{ $invoice->appointment?->doctor?->user?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">₹{{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-4 py-3 text-green-600">₹{{ number_format($invoice->total_paid, 2) }}</td>
                        <td class="px-4 py-3 {{ $invoice->balance > 0 ? 'text-red-600 font-semibold' : 'text-gray-500' }}">₹{{ number_format($invoice->balance, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' :
                                   ($invoice->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('patient.invoices.show', $invoice) }}"
                               class="text-primary-600 hover:underline text-xs font-medium">
                                {{ $invoice->status === 'paid' ? 'View' : 'Pay Now' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $invoices->links() }}</div>
    </div>
</div>
@endsection
