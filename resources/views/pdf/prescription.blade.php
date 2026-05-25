<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription - Health Nest</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 14px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .hospital-name { color: #2563eb; font-size: 28px; font-weight: bold; margin: 0; }
        .hospital-info { font-size: 11px; color: #666; margin-top: 5px; }
        .prescription-label { font-size: 20px; font-weight: bold; text-align: center; margin-bottom: 20px; color: #444; text-transform: uppercase; }
        .section { margin-bottom: 25px; }
        .section-title { font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px; color: #2563eb; font-size: 12px; text-transform: uppercase; }
        .grid { width: 100%; margin-bottom: 20px; }
        .grid td { vertical-align: top; }
        .patient-info td { padding: 5px 0; }
        .label { font-weight: bold; color: #555; width: 120px; display: inline-block; }
        .medicine-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .medicine-table th { background: #f8fafc; text-align: left; padding: 10px; font-size: 11px; border-bottom: 2px solid #eee; }
        .medicine-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .footer { position: fixed; bottom: 0; width: 100%; border-top: 1px solid #eee; padding-top: 10px; font-size: 10px; color: #999; text-align: center; }
        .doctor-signature { text-align: right; margin-top: 50px; }
        .doctor-name { font-weight: bold; margin-bottom: 2px; }
        .doctor-reg { font-size: 11px; color: #777; }
        .rx-symbol { font-size: 32px; font-weight: bold; color: #2563eb; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <p class="hospital-name">Health Nest</p>
                    <p class="hospital-info">
                        Integrated Healthcare Portal<br>
                        123 Medical Plaza, Health City<br>
                        Phone: +91 98765 43210 | Email: care@healthnest.com
                    </p>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div style="font-size: 12px; color: #444;">
                        <strong>Date:</strong> {{ $prescription->prescription_date->format('d M Y') }}<br>
                        <strong>Ref ID:</strong> #PRE-{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="prescription-label">Medical Prescription</div>

    <div class="section">
        <table class="grid patient-info">
            <tr>
                <td style="width: 50%;">
                    <p><span class="label">Patient Name:</span> <strong>{{ $prescription->patient->user->name }}</strong></p>
                    <p><span class="label">Patient ID:</span> #PAT-{{ str_pad($prescription->patient->id, 5, '0', STR_PAD_LEFT) }}</p>
                </td>
                <td style="width: 50%;">
                    <p><span class="label">Age/Sex:</span> {{ $prescription->patient->age ?? 'N/A' }}Y / {{ ucfirst($prescription->patient->gender ?? 'N/A') }}</p>
                    <p><span class="label">Contact:</span> {{ $prescription->patient->user->phone ?? 'N/A' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="rx-symbol">℞</div>
        <table class="medicine-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Medicine Name</th>
                    <th style="width: 20%;">Dosage</th>
                    <th style="width: 20%;">Frequency</th>
                    <th style="width: 20%;">Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->items as $p)
                <tr>
                    <td>
                        <strong>{{ $p->medication_name }}</strong><br>
                        <small style="color: #777;">{{ $p->medication_type ?? 'Medicine' }}</small>
                    </td>
                    <td>{{ $p->dosage }}</td>
                    <td>{{ $p->frequency }}</td>
                    <td>{{ $p->duration }}</td>
                </tr>
                @if($p->instructions)
                <tr>
                    <td colspan="4" style="border-bottom: 1px solid #f9f9f9; padding-top: 0; font-size: 11px; color: #666;">
                        <em>Instructions: {{ $p->instructions }}</em>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    @if($prescription->notes)
    <div class="section">
        <div class="section-title">Advice / Next Steps</div>
        <div style="padding-left: 5px;">
            <p>{{ $prescription->notes }}</p>
        </div>
    </div>
    @endif

    <div class="doctor-signature">
        <div class="doctor-name">{{ $prescription->doctor->user->display_name }}</div>
        <div class="doctor-reg">{{ $prescription->doctor->specialization }} | Reg: {{ $prescription->doctor->license_number }}</div>
    </div>

    <div class="footer">
        This is a digitally generated prescription. It does not require a physical signature if validated online.<br>
        Powered by Health Nest - Your Digital Healthcare Partner
    </div>
</body>
</html>
