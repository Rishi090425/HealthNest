@extends('layouts.app')
@section('title', 'New Medication')
@section('page-title', 'Add to Inventory')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-8 border border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.inventory.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Medication Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Paracetamol 500mg"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Generic Name</label>
                    <input type="text" name="generic_name" placeholder="Active ingredient..."
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Category *</label>
                    <select name="category" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                        <option value="Analgesics">Analgesics</option>
                        <option value="Antibiotics">Antibiotics</option>
                        <option value="Antiseptics">Antiseptics</option>
                        <option value="Antivirals">Antivirals</option>
                        <option value="Hormones">Hormones</option>
                        <option value="Vitamins">Vitamins</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Unit Type *</label>
                    <input type="text" name="unit" required placeholder="e.g. tablet, bottle, ml"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Unit Price (₹) *</label>
                    <input type="number" step="0.01" name="unit_price" required placeholder="0.00"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                </div>
                <div class="col-span-1">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Initial Stock *</label>
                    <input type="number" name="current_stock" required placeholder="0"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                </div>
                <div class="col-span-1">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Reorder Level *</label>
                    <input type="number" name="reorder_level" required placeholder="10"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="{{ route('admin.inventory.index') }}" class="flex-1 text-center py-3.5 rounded-2xl border border-gray-200 dark:border-gray-600 text-sm font-bold text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">Cancel</a>
                <button type="submit" class="flex-[2] bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-primary-200 transition-all">
                    Add Medication
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
