@extends('layouts.app')
@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-800">All Appointments</h2>
        <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <i class="fas fa-plus"></i> Book Appointment
        </a>
    </div>
    <!-- Filter -->
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">Filter</button>
        <a href="{{ route('patient.appointments.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">Reset</a>
    </form>
    <div class="space-y-3">
        @forelse($appointments as $appt)
            <div x-data="{ showPayment: false, method: 'upi' }" class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-blue-250 hover:bg-blue-50/10 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-600 flex-shrink-0">
                        {{ strtoupper(substr($appt->doctor->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">Dr. {{ $appt->doctor->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ $appt->doctor->specialization }}</span>
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full capitalize {{ $appt->status_badge }}">{{ $appt->status }}</span>
                            
                            {{-- Payment Badge --}}
                            @if($appt->status === 'completed' && $appt->invoice)
                                @if($appt->invoice->status === 'paid')
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>Paid
                                    </span>
                                @else
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 animate-pulse">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Payment Pending
                                    </span>
                                @endif
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ $appt->appointment_date->format('d M Y') }}
                            <i class="fas fa-clock ml-3 mr-1"></i>{{ $appt->appointment_time }}
                        </div>
                        @if($appt->reason)<div class="text-xs text-gray-400 mt-0.5">{{ $appt->reason }}</div>@endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(in_array($appt->status, ['pending', 'approved']))
                            <form method="POST" action="{{ route('patient.appointments.destroy', $appt) }}" onsubmit="return confirm('Cancel this appointment?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-times mr-1"></i>Cancel
                                </button>
                            </form>
                        @endif
                        @if($appt->status === 'approved' && $appt->video_room_id)
                            <a href="{{ route('video-room.show', $appt) }}" class="text-xs bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                <i class="fas fa-video mr-1"></i> Join Video
                            </a>
                        @endif
                        
                        {{-- Complete & Unpaid -> Show Pay Now Toggle --}}
                        @if($appt->status === 'completed' && $appt->invoice && $appt->invoice->status !== 'paid')
                            <button @click="showPayment = !showPayment" class="text-xs bg-primary-600 hover:bg-primary-700 text-white font-semibold px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 shadow-sm">
                                <i class="fas fa-wallet"></i> Pay Now
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Collapsable Embedded Payment Form --}}
                @if($appt->status === 'completed' && $appt->invoice && $appt->invoice->status !== 'paid')
                <div x-show="showPayment" x-collapse x-transition class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/50 space-y-4">
                    <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                        <i class="fas fa-wallet text-primary-500"></i> Choose Payment Option for ₹{{ number_format($appt->invoice->balance, 2) }}
                    </h4>
                    
                    {{-- 5 Payment Options tabs --}}
                    <div class="grid grid-cols-5 gap-1.5 sm:gap-2">
                        <button type="button" @click="method = 'online'" :class="method === 'online' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-2 rounded-lg border transition-all">
                            <i class="fas fa-credit-card text-xs sm:text-sm mb-1" :class="method === 'online' ? 'text-primary-600' : 'text-gray-400'"></i>
                            <span class="text-[9px] font-bold truncate max-w-full" :class="method === 'online' ? 'text-primary-700' : 'text-gray-500'">Online</span>
                        </button>
                        <button type="button" @click="method = 'upi'" :class="method === 'upi' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-2 rounded-lg border transition-all">
                            <i class="fas fa-qrcode text-xs sm:text-sm mb-1" :class="method === 'upi' ? 'text-primary-600' : 'text-gray-400'"></i>
                            <span class="text-[9px] font-bold truncate max-w-full" :class="method === 'upi' ? 'text-primary-700' : 'text-gray-500'">Scanner</span>
                        </button>
                        <button type="button" @click="method = 'cash_at_hospital'" :class="method === 'cash_at_hospital' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-2 rounded-lg border transition-all">
                            <i class="fas fa-hospital text-xs sm:text-sm mb-1" :class="method === 'cash_at_hospital' ? 'text-primary-600' : 'text-gray-400'"></i>
                            <span class="text-[9px] font-bold truncate max-w-full" :class="method === 'cash_at_hospital' ? 'text-primary-700' : 'text-gray-500'">Hospital</span>
                        </button>
                        <button type="button" @click="method = 'cash'" :class="method === 'cash' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-2 rounded-lg border transition-all">
                            <i class="fas fa-money-bill-wave text-xs sm:text-sm mb-1" :class="method === 'cash' ? 'text-primary-600' : 'text-gray-400'"></i>
                            <span class="text-[9px] font-bold truncate max-w-full" :class="method === 'cash' ? 'text-primary-700' : 'text-gray-500'">Cash</span>
                        </button>
                        <button type="button" @click="method = 'pay_later'" :class="method === 'pay_later' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-2 rounded-lg border transition-all">
                            <i class="fas fa-clock text-xs sm:text-sm mb-1" :class="method === 'pay_later' ? 'text-primary-600' : 'text-gray-400'"></i>
                            <span class="text-[9px] font-bold truncate max-w-full" :class="method === 'pay_later' ? 'text-primary-700' : 'text-gray-500'">Later</span>
                        </button>
                    </div>

                    {{-- Dynamic method panel container --}}
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700/50">
                        
                        {{-- Online Payment --}}
                        <div x-show="method === 'online'" class="text-center space-y-2 py-1">
                            <h5 class="text-xs font-bold text-gray-800 dark:text-gray-200">Secure Online Payment</h5>
                            <p class="text-[10px] text-gray-400">Instantly pay using Credit/Debit cards, UPI, or Net Banking via Razorpay.</p>
                            <button type="button" id="rzp-btn-{{ $appt->id }}" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 rounded-lg text-xs shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                <i class="fas fa-lock"></i> PAY ₹{{ number_format($appt->invoice->balance, 2) }} NOW
                            </button>
                            
                            <script>
                                document.getElementById('rzp-btn-{{ $appt->id }}').onclick = function(e) {
                                    e.preventDefault();
                                    fetch("{{ route('patient.razorpay.order', $appt->invoice) }}", {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(res => res.json()).then(data => {
                                        if (data.error) { alert("Error: " + data.error); return; }
                                        var options = {
                                            "key": data.key_id,
                                            "amount": data.amount,
                                            "currency": "INR",
                                            "name": "Health Nest",
                                            "description": "Appointment Payment #{{ str_pad($appt->invoice->id, 5, '0', STR_PAD_LEFT) }}",
                                            "order_id": data.order_id,
                                            "handler": function (response){
                                                fetch("{{ route('patient.razorpay.verify', $appt->invoice) }}", {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        razorpay_payment_id: response.razorpay_payment_id,
                                                        razorpay_order_id: response.razorpay_order_id,
                                                        razorpay_signature: response.razorpay_signature
                                                    })
                                                }).then(res => res.json()).then(data => {
                                                    if(data.success) { window.location.reload(); }
                                                    else { alert("Verification Failed: " + data.error); }
                                                });
                                            },
                                            "prefill": {
                                                "name": data.name,
                                                "email": data.email,
                                                "contact": data.phone
                                            },
                                            "theme": { "color": "#2563eb" }
                                        };
                                        var rzp1 = new Razorpay(options);
                                        rzp1.open();
                                    });
                                }
                            </script>
                        </div>

                        {{-- Scanner / UPI (FIXED PARSING GENERATOR) --}}
                        <div x-show="method === 'upi'" class="text-center space-y-3">
                            <p class="text-[11px] text-gray-500 font-medium">Scan QR to pay ₹{{ number_format($appt->invoice->balance, 2) }}</p>
                            
                            @php
                                $upiId = \App\Models\Setting::get('upi_id', 'rishi.kumar14125@okaxis');
                                $upiUrl = "upi://pay?pa=" . $upiId . "&pn=HealthNest&am=" . $appt->invoice->balance . "&cu=INR";
                                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($upiUrl);
                            @endphp

                            <div class="inline-block p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                                <img src="{{ $qrCodeUrl }}" alt="Payment QR" class="w-36 h-36 mx-auto">
                            </div>

                            <form method="POST" action="{{ route('patient.invoices.pay', $appt->invoice) }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="payment_method" value="upi">
                                <input type="hidden" name="amount" value="{{ $appt->invoice->balance }}">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg text-xs shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                    <i class="fas fa-check-circle"></i> I HAVE PAID (CONFIRM)
                                </button>
                            </form>
                        </div>

                        {{-- Pay at Hospital --}}
                        <div x-show="method === 'cash_at_hospital'" class="text-center space-y-3 py-1">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-hospital text-sm"></i>
                            </div>
                            <h5 class="text-xs font-bold text-gray-800 dark:text-gray-200">Pay at Hospital Counter</h5>
                            <p class="text-[11px] text-gray-500">Visit the hospital reception desk to pay. The Admin will record your receipt immediately.</p>
                            <form method="POST" action="{{ route('patient.invoices.pay', $appt->invoice) }}">
                                @csrf
                                <input type="hidden" name="payment_method" value="cash_at_hospital">
                                <button type="submit" class="text-primary-600 text-xs font-bold hover:underline">
                                    Notify Hospital I'm Coming
                                </button>
                            </form>
                        </div>

                        {{-- Cash payment demo --}}
                        <div x-show="method === 'cash'" class="space-y-3">
                            <form method="POST" action="{{ route('patient.invoices.pay', $appt->invoice) }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="payment_method" value="cash">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-600 dark:text-gray-400 mb-1">Paying Amount (₹)</label>
                                    <input type="number" name="amount" step="0.01" value="{{ $appt->invoice->balance }}" 
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-1.5 text-xs">
                                </div>
                                <button type="submit" class="w-full bg-gray-800 dark:bg-gray-700 text-white font-bold py-2 rounded-lg text-xs hover:bg-gray-900 transition-colors">
                                    Record Cash Payment
                                </button>
                            </form>
                        </div>

                        {{-- Pay Later --}}
                        <div x-show="method === 'pay_later'" class="text-center space-y-3 py-1">
                            <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-clock text-sm"></i>
                            </div>
                            <h5 class="text-xs font-bold text-gray-800 dark:text-gray-200">Request Pay Later</h5>
                            <p class="text-[11px] text-gray-500">Postpone the payment for up to 7 days.</p>
                            <form method="POST" action="{{ route('patient.invoices.pay', $appt->invoice) }}">
                                @csrf
                                <input type="hidden" name="payment_method" value="pay_later">
                                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 rounded-lg text-xs shadow-sm transition-colors">
                                    CONFIRM PAY LATER
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                @endif
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-calendar-check text-5xl mb-4 block opacity-20"></i>
                <p>No appointments found. <a href="{{ route('patient.appointments.create') }}" class="text-blue-600 hover:underline">Book one now!</a></p>
            </div>
        @endforelse
    </div>
    @if($appointments->hasPages())<div class="mt-6">{{ $appointments->withQueryString()->links() }}</div>@endif
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection
