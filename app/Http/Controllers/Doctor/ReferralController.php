<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReferralRequest;
use App\Models\Referral;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\AuditLog;

class ReferralController extends Controller
{
    public function index()
    {
        $doctor    = auth()->user()->doctor;
        $referrals = Referral::with('patient.user', 'referredDoctor.user')
            ->where('referring_doctor_id', $doctor->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('doctor.referrals.index', compact('referrals'));
    }

    public function create()
    {
        $doctor   = auth()->user()->doctor;
        $patients = Patient::with('user')
            ->whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))
            ->get();
        $doctors  = Doctor::with('user')
            ->where('id', '!=', $doctor->id)
            ->where('status', 'active')
            ->get();

        return view('doctor.referrals.create', compact('patients', 'doctors'));
    }

    public function store(StoreReferralRequest $request)
    {
        $doctor   = auth()->user()->doctor;
        $referral = Referral::create(array_merge(
            $request->validated(),
            ['referring_doctor_id' => $doctor->id, 'status' => 'pending']
        ));

        AuditLog::record('Sent Referral', 'Referral', $referral->id);

        return redirect()->route('doctor.referrals.index')
            ->with('success', 'Referral sent successfully.');
    }

    public function show(Referral $referral)
    {
        $referral->load('patient.user', 'referredDoctor.user', 'referringDoctor.user');
        return view('doctor.referrals.show', compact('referral'));
    }
}
