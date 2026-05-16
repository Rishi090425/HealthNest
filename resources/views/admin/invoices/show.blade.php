@extends('layouts.app')
@section('title', 'Invoice #' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Invoice Detail')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8">
        <div class="flex justify-between items-start border-b dark:border-gray-700 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Invoice</h1>
                <p class="text-sm text-gray-500 mt-1">#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                <div class="mt-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : ($invoice->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ $invoice->status }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Health Nest</p>
                <p class="text-xs text-gray-500">123 Health Ave, Medical City</p>
                <p class="text-xs text-gray-500">contact@healthcare.com</p>
                <p class="text-xs text-gray-500 mt-4">Issued: {{ $invoice->issued_date?->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">Due: {{ $invoice->due_date?->format('d M Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Billed To:</p>
                <p class="font-bold text-gray-900 dark:text-white">{{ $invoice->patient->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $invoice->patient->user->email }}</p>
                <p class="text-sm text-gray-500">{{ $invoice->patient->user->phone }}</p>
            </div>
            @if($invoice->appointment)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Service Details:</p>
                <p class="text-sm text-gray-900 dark:text-white font-medium">Consultation with Dr. {{ $invoice->appointment->doctor->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $invoice->appointment->appointment_date->format('d M Y') }}</p>
            </div>
            @endif
        </div>

        <table class="w-full mb-8">
            <thead class="border-b dark:border-gray-700">
                <tr>
                    <th class="text-left py-3 text-xs font-bold text-gray-400 uppercase">Description</th>
                    <th class="text-right py-3 text-xs font-bold text-gray-400 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                <tr>
                    <td class="py-4 text-sm text-gray-900 dark:text-white">
                        Medical Consultation / Service
                        @if($invoice->notes)
                        <p class="text-xs text-gray-400 mt-1 italic">{{ $invoice->notes }}</p>
                        @endif
                    </td>
                    <td class="py-4 text-right text-sm font-medium text-gray-900 dark:text-white">₹{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot class="border-t-2 border-gray-100 dark:border-gray-700">
                <tr>
                    <td class="py-4 text-right text-sm font-bold text-gray-900 dark:text-white">Total</td>
                    <td class="py-4 text-right text-lg font-bold text-primary-600">₹{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-right text-xs text-green-600">Total Paid</td>
                    <td class="py-2 text-right text-xs font-medium text-green-600">₹{{ number_format($invoice->total_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-right text-sm font-bold text-gray-900 dark:text-white">Balance Due</td>
                    <td class="py-2 text-right text-sm font-bold text-red-600">₹{{ number_format($invoice->balance, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if($invoice->payments->count() > 0)
        <div class="mt-10">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Payment History</h3>
            <div class="space-y-3">
                @foreach($invoice->payments as $payment)
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">₹{{ number_format($payment->amount, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->payment_date->format('d M Y, h:i A') }} via {{ ucfirst($payment->payment_method) }}</p>
                    </div>
                    <span class="text-xs font-mono text-gray-400">TXN: {{ $payment->transaction_id }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="flex justify-center gap-4">
        <button onclick="window.print()" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <i class="fas fa-print mr-2"></i>Print Invoice
        </button>
        <a href="{{ route('admin.invoices.index') }}" class="bg-primary-600 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-700 transition-colors">
            Back to Invoices
        </a>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .max-w-3xl, .max-w-3xl * { visibility: visible; }
    .max-w-3xl { position: absolute; left: 0; top: 0; width: 100%; }
    .flex.justify-center { display: none; }
}
</style>
@endsection
