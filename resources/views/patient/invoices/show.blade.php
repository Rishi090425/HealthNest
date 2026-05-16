@extends('layouts.app')
@section('title', 'Invoice #' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Invoice Detail')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Invoice Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-sm text-gray-500 mt-1">Issued: {{ $invoice->issued_date?->format('d M Y') }}</p>
                @if($invoice->due_date)
                <p class="text-sm text-gray-500">Due: {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
            </div>
            <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' :
                   ($invoice->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>

        @if($invoice->appointment)
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-6">
            <p class="text-xs text-gray-500 mb-1">Appointment</p>
            <p class="font-medium text-gray-800 dark:text-gray-100">
                Dr. {{ $invoice->appointment->doctor->user->name }} · {{ $invoice->appointment->appointment_date->format('d M Y') }}
            </p>
        </div>
        @endif

        <div class="space-y-3 mb-6">
            <div class="flex justify-between py-2 border-b dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Total Amount</span>
                <span class="font-bold text-gray-900 dark:text-white text-lg">₹{{ number_format($invoice->amount, 2) }}</span>
            </div>
            <div class="flex justify-between py-2 border-b dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Total Paid</span>
                <span class="font-semibold text-green-600">₹{{ number_format($invoice->total_paid, 2) }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600 dark:text-gray-400">Balance Due</span>
                <span class="font-bold text-xl {{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                    ₹{{ number_format($invoice->balance, 2) }}
                </span>
            </div>
        </div>

        {{-- Payment History --}}
        @if($invoice->payments->count() > 0)
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Payment History</h3>
        <div class="space-y-2 mb-6">
            @foreach($invoice->payments as $payment)
            <div class="flex justify-between items-center bg-green-50 dark:bg-green-900/20 rounded-lg px-4 py-2">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">₹{{ number_format($payment->amount, 2) }} via {{ ucfirst(str_replace('_',' ', $payment->payment_method)) }}</p>
                    <p class="text-xs text-gray-500">{{ $payment->payment_date->format('d M Y, H:i') }}</p>
                </div>
                <span class="text-xs font-mono text-gray-400">{{ $payment->transaction_id }}</span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Enhanced Payment Section --}}
        @if($invoice->balance > 0)
        <div class="border-t dark:border-gray-700 pt-6 mt-6">
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                <i class="fas fa-wallet text-primary-500"></i>
                Choose Payment Method
            </h3>

            <div x-data="{ method: 'upi' }" class="space-y-6">
                {{-- Method Selection Icons --}}
                <div class="grid grid-cols-5 gap-3">
                    <button @click="method = 'online'" :class="method === 'online' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all">
                        <i class="fas fa-credit-card text-lg mb-1" :class="method === 'online' ? 'text-primary-600' : 'text-gray-400'"></i>
                        <span class="text-[10px] font-bold" :class="method === 'online' ? 'text-primary-700 dark:text-primary-300' : 'text-gray-500'">Online</span>
                    </button>
                    <button @click="method = 'upi'" :class="method === 'upi' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all">
                        <i class="fas fa-qrcode text-lg mb-1" :class="method === 'upi' ? 'text-primary-600' : 'text-gray-400'"></i>
                        <span class="text-[10px] font-bold" :class="method === 'upi' ? 'text-primary-700 dark:text-primary-300' : 'text-gray-500'">Scanner</span>
                    </button>
                    <button @click="method = 'cash_at_hospital'" :class="method === 'cash_at_hospital' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all">
                        <i class="fas fa-hospital text-lg mb-1" :class="method === 'cash_at_hospital' ? 'text-primary-600' : 'text-gray-400'"></i>
                        <span class="text-[10px] font-bold" :class="method === 'cash_at_hospital' ? 'text-primary-700 dark:text-primary-300' : 'text-gray-500'">Hospital</span>
                    </button>
                    <button @click="method = 'cash'" :class="method === 'cash' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all">
                        <i class="fas fa-money-bill-wave text-lg mb-1" :class="method === 'cash' ? 'text-primary-600' : 'text-gray-400'"></i>
                        <span class="text-[10px] font-bold" :class="method === 'cash' ? 'text-primary-700 dark:text-primary-300' : 'text-gray-500'">Cash</span>
                    </button>
                    <button @click="method = 'pay_later'" :class="method === 'pay_later' ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'border-gray-200 dark:border-gray-700'" class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all">
                        <i class="fas fa-clock text-lg mb-1" :class="method === 'pay_later' ? 'text-primary-600' : 'text-gray-400'"></i>
                        <span class="text-[10px] font-bold" :class="method === 'pay_later' ? 'text-primary-700 dark:text-primary-300' : 'text-gray-500'">Later</span>
                    </button>
                </div>

                {{-- Payment Views --}}
                <div class="bg-gray-50 dark:bg-gray-900/40 rounded-2xl p-6 border border-gray-100 dark:border-gray-700/50">
                    
                    {{-- Online View (Razorpay) --}}
                    <div x-show="method === 'online'" class="text-center space-y-4 py-4">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 text-primary-600 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-shield-alt text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 dark:text-gray-200">Secure Online Payment</h4>
                        <p class="text-sm text-gray-500 px-4">Pay safely using your Credit/Debit Card, Net Banking, or UPI via Razorpay.</p>
                        
                        <button id="rzp-button" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary-900/20 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i>
                            PAY ₹{{ number_format($invoice->balance, 2) }} NOW
                        </button>
                        
                        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                        <script>
                            document.getElementById('rzp-button').onclick = function(e) {
                                e.preventDefault();
                                
                                fetch("{{ route('patient.razorpay.order', $invoice) }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                }).then(res => res.json()).then(data => {
                                    if (data.error) {
                                        alert("Error: " + data.error);
                                        return;
                                    }

                                    var options = {
                                        "key": data.key_id,
                                        "amount": data.amount,
                                        "currency": "INR",
                                        "name": "Health Nest",
                                        "description": "Invoice Payment #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}",
                                        "order_id": data.order_id,
                                        "handler": function (response){
                                            fetch("{{ route('patient.razorpay.verify', $invoice) }}", {
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
                                                if(data.success) {
                                                    window.location.reload();
                                                } else {
                                                    alert("Verification Failed: " + data.error);
                                                }
                                            });
                                        },
                                        "prefill": {
                                            "name": data.name,
                                            "email": data.email,
                                            "contact": data.phone
                                        },
                                        "theme": {
                                            "color": "#2563eb"
                                        }
                                    };
                                    var rzp1 = new Razorpay(options);
                                    rzp1.open();
                                });
                            }
                        </script>
                    </div>

                    {{-- UPI / Scanner View --}}
                    <div x-show="method === 'upi'" class="text-center space-y-4">
                        <p class="text-xs text-gray-500 mb-2 font-medium">Scan QR to pay ₹{{ number_format($invoice->balance, 2) }}</p>
                        <div class="inline-block p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                            {{-- Placeholder for User QR --}}
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=rishi.kumar14125@okaxis%26pn=HealthNest%26am={{ $invoice->balance }}%26cu=INR" 
                                 alt="Payment QR" class="w-48 h-48">
                        </div>
                        <div class="flex flex-col gap-2">
                            <form method="POST" action="{{ route('patient.invoices.pay', $invoice) }}">
                                @csrf
                                <input type="hidden" name="payment_method" value="upi">
                                <input type="hidden" name="amount" value="{{ $invoice->balance }}">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-green-900/20 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    I HAVE PAID (CONFIRM)
                                </button>
                            </form>
                            <p class="text-[10px] text-gray-400 italic">This is a manual confirmation. Real online payment is available in the "Online" tab.</p>
                        </div>
                    </div>

                    {{-- Hospital View --}}
                    <div x-show="method === 'cash_at_hospital'" class="text-center space-y-4 py-4">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-hospital text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 dark:text-gray-200">Pay at Hospital Reception</h4>
                        <p class="text-sm text-gray-500 px-4">You can visit the hospital billing counter and pay via Cash or Card. The Admin will update your status immediately.</p>
                        <form method="POST" action="{{ route('patient.invoices.pay', $invoice) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="cash_at_hospital">
                            <button type="submit" class="text-primary-600 font-bold text-sm hover:underline">
                                Notify Hospital I'm Coming
                            </button>
                        </form>
                    </div>

                    {{-- Cash View --}}
                    <div x-show="method === 'cash'" class="space-y-4">
                        <form method="POST" action="{{ route('patient.invoices.pay', $invoice) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="cash">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Paying Amount (₹)</label>
                            <input type="number" name="amount" step="0.01" value="{{ $invoice->balance }}" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 mb-4 px-3 py-2 text-sm">
                            <button type="submit" class="w-full bg-gray-800 dark:bg-gray-700 text-white font-bold py-3 rounded-xl hover:bg-gray-900 transition-all">
                                Record Cash Payment
                            </button>
                        </form>
                    </div>

                    {{-- Pay Later View --}}
                    <div x-show="method === 'pay_later'" class="text-center space-y-4 py-4">
                        <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 dark:text-gray-200">Request Pay Later</h4>
                        <p class="text-sm text-gray-500 px-4">Need more time? You can request to pay this invoice within the next 7 days.</p>
                        <form method="POST" action="{{ route('patient.invoices.pay', $invoice) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="pay_later">
                            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-yellow-900/20 transition-all">
                                CONFIRM PAY LATER
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @else
        <div class="text-center py-8 bg-green-50 dark:bg-green-900/10 rounded-2xl border border-green-100 dark:border-green-900/30 mt-6">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-2xl"></i>
            </div>
            <h4 class="font-bold text-green-800 dark:text-green-300">Payment Completed!</h4>
            <p class="text-sm text-green-600 dark:text-green-400">This invoice has been fully settled. Thank you!</p>
        </div>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('patient.invoices.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <i class="fas fa-arrow-left mr-1"></i>Back to all invoices
        </a>
    </div>
</div>
@endsection
