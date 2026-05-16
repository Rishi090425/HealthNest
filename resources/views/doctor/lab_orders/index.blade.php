@extends('layouts.app')
@section('title', 'Lab Orders')
@section('page-title', 'Lab Orders')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Order lab tests and annotate results.</p>
        <a href="{{ route('doctor.lab-orders.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>New Lab Order
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Patient</th>
                    <th class="px-4 py-3 text-left font-medium">Test Type</th>
                    <th class="px-4 py-3 text-left font-medium">Order Date</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($labOrders as $order)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $order->patient->user->name }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $order->test_type }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $order->order_date->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('doctor.lab-orders.show', $order) }}" class="text-primary-600 hover:underline text-xs font-medium">
                            {{ $order->status === 'pending' ? 'Annotate Result' : 'View Results' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No lab orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $labOrders->links() }}</div>
    </div>
</div>
@endsection
