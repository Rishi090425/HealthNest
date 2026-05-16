@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices & Payments')
@section('content')
<div class="space-y-5">
    {{-- KPI Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-primary-100 dark:bg-primary-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-primary-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Revenue</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-red-100 dark:bg-red-900/40 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-red-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Pending Amount</p>
                <p class="text-xl font-bold text-red-600">₹{{ number_format($pendingAmount, 2) }}</p>
            </div>
        </div>
        <div class="flex items-center justify-end">
            <a href="{{ route('admin.invoices.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-3 rounded-xl transition-colors">
                <i class="fas fa-plus mr-2"></i>Create Invoice
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Invoice #</th>
                        <th class="px-4 py-3 text-left font-medium">Patient</th>
                        <th class="px-4 py-3 text-left font-medium">Issued</th>
                        <th class="px-4 py-3 text-left font-medium">Amount</th>
                        <th class="px-4 py-3 text-left font-medium">Paid</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 font-mono text-gray-700 dark:text-gray-300">#{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $inv->patient->user->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $inv->issued_date?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 font-semibold">₹{{ number_format($inv->amount, 2) }}</td>
                        <td class="px-4 py-3 text-green-600">₹{{ number_format($inv->total_paid, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $inv->status === 'paid' ? 'bg-green-100 text-green-700' : ($inv->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($inv->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.invoices.show', $inv) }}" class="text-primary-600 hover:underline text-xs font-medium">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $invoices->links() }}</div>
    </div>
</div>
@endsection
