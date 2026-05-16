<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabOrderRequest;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\Patient;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LabOrderController extends Controller
{
    public function index()
    {
        $doctor    = auth()->user()->doctor;
        $labOrders = LabOrder::with('patient.user', 'results')
            ->where('doctor_id', $doctor->id)
            ->orderByDesc('order_date')
            ->paginate(15);

        return view('doctor.lab_orders.index', compact('labOrders'));
    }

    public function create()
    {
        $doctor   = auth()->user()->doctor;
        $patients = Patient::with('user')
            ->whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))
            ->get();

        return view('doctor.lab_orders.create', compact('patients'));
    }

    public function store(StoreLabOrderRequest $request)
    {
        $doctor   = auth()->user()->doctor;
        $labOrder = LabOrder::create(array_merge(
            $request->validated(),
            ['doctor_id' => $doctor->id, 'status' => 'pending']
        ));

        AuditLog::record('Created Lab Order', 'LabOrder', $labOrder->id);

        return redirect()->route('doctor.lab-orders.show', $labOrder)
            ->with('success', 'Lab order created.');
    }

    public function show(LabOrder $labOrder)
    {
        $this->authorizeDoctor($labOrder);
        $labOrder->load('patient.user', 'results');

        return view('doctor.lab_orders.show', compact('labOrder'));
    }

    public function annotate(Request $request, LabOrder $labOrder)
    {
        $this->authorizeDoctor($labOrder);
        $request->validate([
            'result_date'  => 'required|date',
            'result_value' => 'required|string|max:2000',
            'normal_range' => 'nullable|string|max:255',
            'flag'         => 'nullable|in:normal,low,high,critical',
            'report_path'  => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('report_path')) {
            $path = $request->file('report_path')->store('lab-reports', 'private');
        }

        LabResult::create([
            'lab_order_id' => $labOrder->id,
            'result_date'  => $request->result_date,
            'result_value' => $request->result_value,
            'normal_range' => $request->normal_range,
            'flag'         => $request->flag,
            'report_path'  => $path,
        ]);

        $labOrder->update(['status' => 'completed']);
        AuditLog::record('Annotated Lab Result', 'LabOrder', $labOrder->id);

        return back()->with('success', 'Lab result recorded.');
    }

    private function authorizeDoctor(LabOrder $o): void
    {
        if ($o->doctor_id !== auth()->user()->doctor?->id) {
            abort(403);
        }
    }
}
