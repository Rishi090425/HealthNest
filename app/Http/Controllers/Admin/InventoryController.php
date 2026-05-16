<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\InventoryLevel;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $medications = Medication::with('inventory')->paginate(20);
        $lowStockCount = InventoryLevel::whereColumn('current_stock', '<=', 'reorder_level')->count();

        return view('admin.inventory.index', compact('medications', 'lowStockCount'));
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'generic_name'  => 'nullable|string|max:255',
            'category'      => 'required|string',
            'unit'          => 'required|string',
            'unit_price'    => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $medication = Medication::create($request->only(['name', 'generic_name', 'category', 'unit', 'unit_price']));

        $medication->inventory()->create([
            'current_stock' => $request->current_stock,
            'reorder_level' => $request->reorder_level,
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Medication added to inventory.');
    }

    public function updateStock(Request $request, Medication $medication)
    {
        $request->validate(['change' => 'required|integer']);

        $inventory = $medication->inventory;
        $inventory->current_stock += $request->change;
        $inventory->save();

        return back()->with('success', 'Stock updated for ' . $medication->name);
    }
}
