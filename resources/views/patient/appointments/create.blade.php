@extends('layouts.app')
@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-plus text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Book an Appointment</h2>
                <p class="text-sm text-gray-500">Fill in the details to schedule your visit</p>
            </div>
        </div>
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('patient.appointments.store') }}" class="space-y-5">
            @csrf
            <!-- Doctor Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @foreach($doctors as $doctor)
                        <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 border-gray-100 hover:border-blue-200">
                            <input type="radio" name="doctor_id" value="{{ $doctor->id }}" data-fee="{{ $doctor->consultation_fee }}" data-name="{{ $doctor->user->display_name }}" class="text-blue-600 flex-shrink-0" {{ old('doctor_id') == $doctor->id ? 'checked' : '' }} required>
                            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-600 flex-shrink-0">
                                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-800">{{ $doctor->user->display_name }}</div>
                                <div class="text-xs text-gray-500">{{ $doctor->specialization }} · {{ $doctor->qualification }}</div>
                            </div>
                            <div class="text-sm font-semibold text-blue-600 flex-shrink-0">₹{{ number_format($doctor->consultation_fee) }}</div>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date *</label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date') }}"
                        min="{{ today()->toDateString() }}" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Time *</label>
                    <select name="appointment_time" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select time...</option>
                        @foreach(['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'] as $time)
                            <option value="{{ $time }}" {{ old('appointment_time') === $time ? 'selected' : '' }}>{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Visit *</label>
                <textarea name="reason" rows="3" required maxlength="500"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Describe your symptoms or reason for the visit...">{{ old('reason') }}</textarea>
            </div>

            <!-- Payment Method Selection -->
            <div class="border-t border-gray-100 pt-5">
                <label class="block text-sm font-medium text-gray-700 mb-3">Choose Payment Method *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 border-gray-100 hover:border-blue-200">
                        <input type="radio" name="payment_method" value="cash" checked class="text-blue-600 flex-shrink-0" required>
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Cash / Pay at Clinic</div>
                            <div class="text-xs text-gray-500">Pay directly after your consultation</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 border-gray-100 hover:border-blue-200">
                        <input type="radio" name="payment_method" value="online" class="text-blue-600 flex-shrink-0" required>
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Online Payment (UPI)</div>
                            <div class="text-xs text-gray-500">Scan UPI QR Code to pay instantly</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Interactive UPI Scanner Panel -->
            <div id="upi-scanner-panel" class="hidden transition-all duration-300 transform scale-95 opacity-0 bg-gray-50 border border-gray-100 rounded-2xl p-5 space-y-4">
                <div class="text-center">
                    <h3 class="text-sm font-bold text-gray-800">Scan & Pay via UPI</h3>
                    <p class="text-xs text-gray-500">Scan this QR Code using any UPI App (GPay, PhonePe, Paytm)</p>
                </div>

                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative">
                        <img id="qr-code-img" src="" alt="UPI QR Code" class="w-40 h-40">
                    </div>

                    <div class="text-center">
                        <div class="text-xs text-gray-400 font-medium">Payable Amount</div>
                        <div id="payable-amount" class="text-xl font-extrabold text-blue-600">₹0</div>
                        <div id="payable-doctor" class="text-xs text-gray-500 mt-0.5">For Doctor</div>
                    </div>
                </div>

                <!-- UPI Apps Row -->
                <div class="flex items-center justify-center gap-6 border-y border-gray-200/50 py-3 text-gray-400 text-xs font-semibold">
                    <span class="flex items-center gap-1.5"><i class="fab fa-google-pay text-base text-[#4285F4]"></i> GPay</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-wallet text-base text-[#5f259f]"></i> PhonePe</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-money-bill-wave text-base text-[#00baf2]"></i> Paytm</span>
                </div>

                <!-- Transaction ID input -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Enter Transaction ID / UTR * (12-digit number)</label>
                    <input type="text" name="payment_transaction_id" id="payment_transaction_id" placeholder="e.g. 345678901234"
                        pattern="\d{12}" minlength="12" maxlength="20"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-[10px] text-gray-400 mt-1">Please enter the 12-digit transaction number shown in your UPI app receipt after payment.</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" id="book-btn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>Book Appointment
                </button>
                <a href="{{ route('patient.appointments.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const doctorRadios = document.querySelectorAll('input[name="doctor_id"]');
                const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
                const upiPanel = document.getElementById('upi-scanner-panel');
                const qrCodeImg = document.getElementById('qr-code-img');
                const payableAmount = document.getElementById('payable-amount');
                const payableDoctor = document.getElementById('payable-doctor');
                const transactionInput = document.getElementById('payment_transaction_id');

                function updateUPI() {
                    const selectedDoc = document.querySelector('input[name="doctor_id"]:checked');
                    const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

                    if (selectedPayment === 'online') {
                        upiPanel.classList.remove('hidden');
                        setTimeout(() => {
                            upiPanel.classList.remove('scale-95', 'opacity-0');
                        }, 50);
                        transactionInput.required = true;

                        if (selectedDoc) {
                            const fee = selectedDoc.getAttribute('data-fee');
                            const name = selectedDoc.getAttribute('data-name');
                            
                            payableAmount.innerText = '₹' + parseFloat(fee).toLocaleString('en-IN');
                            payableDoctor.innerText = 'For ' + name;

                            // Generate a real UPI QR Link using QR Server API
                            const upiLink = `upi://pay?pa=healthnest@okaxis&pn=HealthNest&am=${fee}&cu=INR&tn=Consultation+Fee`;
                            qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(upiLink)}`;
                        } else {
                            payableAmount.innerText = 'Select a doctor...';
                            payableDoctor.innerText = '';
                            qrCodeImg.src = '';
                        }
                    } else {
                        upiPanel.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            upiPanel.classList.add('hidden');
                        }, 200);
                        transactionInput.required = false;
                        transactionInput.value = '';
                    }
                }

                // Add event listeners
                doctorRadios.forEach(radio => radio.addEventListener('change', updateUPI));
                paymentRadios.forEach(radio => radio.addEventListener('change', updateUPI));

                // Run initially
                updateUPI();
            });
        </script>
    </div>
</div>
@endsection
