@extends('layouts.app')
@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('content')
<div x-data="{ successAnim: false, successAmount: '0.00' }" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 relative">
    
    {{-- Success Checkmark Overlay Style --}}
    <style>
        @keyframes scaleCircle {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); opacity: 1; }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        @keyframes drawCheck {
            0% { stroke-dashoffset: 48; }
            100% { stroke-dashoffset: 0; }
        }
        .success-overlay {
            backdrop-filter: blur(8px);
            background: rgba(15, 23, 42, 0.6);
        }
        .animate-circle {
            animation: scaleCircle 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .animate-check {
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: drawCheck 0.5s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
        }
    </style>

    {{-- Fullscreen Success Overlay --}}
    <div x-show="successAnim" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center success-overlay"
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl text-center space-y-4 border border-gray-100 dark:border-gray-700/50 transform transition-all duration-300">
            <!-- Success Check Icon -->
            <div class="relative w-20 h-20 mx-auto flex items-center justify-center bg-green-50 dark:bg-green-950/30 rounded-full animate-circle">
                <svg class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path class="animate-check" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Payment Done!</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Scan successful! Payment of <span class="font-bold text-gray-900 dark:text-white">₹<span x-text="successAmount"></span></span> has been verified. Your invoice is marked as PAID.</p>
            
            <div class="pt-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span>
                    Redirecting...
                </span>
            </div>
        </div>
    </div>

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
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $appt->doctor->user->display_name }}</span>
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
                    
                    {{-- 4 Payment Options tabs (Razorpay removed) --}}
                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
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

                             <form id="pay-form-{{ $appt->invoice->id }}" method="POST" action="{{ route('patient.invoices.pay', $appt->invoice) }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="payment_method" value="upi">
                                <input type="hidden" name="amount" value="{{ $appt->invoice->balance }}">
                                <button type="button" @click="successAmount = '{{ number_format($appt->invoice->balance, 2) }}'; successAnim = true; setTimeout(() => document.getElementById('pay-form-{{ $appt->invoice->id }}').submit(), 2000)" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg text-xs shadow-sm flex items-center justify-center gap-1.5 transition-colors animate-pulse">
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


