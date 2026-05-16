@extends('layouts.app')
@section('title', 'Medicine Inventory')
@section('page-title', 'Pharmacy Inventory')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Low Stock Items</p>
                <p class="text-xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $lowStockCount }}</p>
            </div>
        </div>
        <a href="{{ route('admin.inventory.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-primary-200">
            <i class="fas fa-plus mr-2"></i>Add Medication
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 text-left">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Medication Name</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Category</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Stock Level</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider">Unit Price</th>
                        <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-wider text-right">Quick Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($medications as $med)
                    @php 
                        $stock = $med->inventory->current_stock ?? 0;
                        $reorder = $med->inventory->reorder_level ?? 0;
                        $isLow = $stock <= $reorder;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900 dark:text-white">{{ $med->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $med->generic_name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-[10px] font-bold uppercase">
                                {{ $med->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-gray-200 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full {{ $isLow ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ min(100, ($stock/($reorder ?: 1))*50) }}%"></div>
                                </div>
                                <span class="font-bold {{ $isLow ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $stock }}</span>
                                <span class="text-[10px] text-gray-400">/ {{ $med->unit }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-600 dark:text-gray-400">₹{{ number_format($med->unit_price, 2) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.inventory.update-stock', $med) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="change" value="10">
                                    <button type="submit" class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-100 transition-colors" title="+10 Units"><i class="fas fa-plus text-xs"></i></button>
                                </form>
                                <form method="POST" action="{{ route('admin.inventory.update-stock', $med) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="change" value="-10">
                                    <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors" title="-10 Units"><i class="fas fa-minus text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Inventory is empty.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div>{{ $medications->links() }}</div>
</div>
@endsection
