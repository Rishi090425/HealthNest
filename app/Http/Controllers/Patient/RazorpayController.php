<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;

class RazorpayController extends Controller
{
    public function createOrder(Request $request, Invoice $invoice)
    {
        if ($invoice->patient_id !== auth()->user()->patient?->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

        try {
            $order = $api->order->create([
                'receipt'         => 'rcpt_' . $invoice->id,
                'amount'          => $invoice->balance * 100, // Amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // Auto capture
            ]);

            return response()->json([
                'order_id' => $order['id'],
                'amount'   => $order['amount'],
                'key_id'   => config('razorpay.key_id'),
                'name'     => auth()->user()->name,
                'email'    => auth()->user()->email,
                'phone'    => auth()->user()->phone ?? '9999999999',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request, Invoice $invoice)
    {
        $input = $request->all();
        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $attributes = [
                    'razorpay_order_id'   => $input['razorpay_order_id'],
                    'razorpay_payment_id' => $input['razorpay_payment_id'],
                    'razorpay_signature'  => $input['razorpay_signature']
                ];

                $api->utility->verifyPaymentSignature($attributes);

                // Payment is successful, update DB
                Payment::create([
                    'invoice_id'     => $invoice->id,
                    'amount'         => $invoice->balance,
                    'payment_method' => 'razorpay',
                    'transaction_id' => $input['razorpay_payment_id'],
                    'payment_date'   => now(),
                ]);

                $invoice->update(['status' => 'paid']);

                return response()->json(['success' => true, 'message' => 'Payment verified successfully.']);
            } catch (Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => false, 'error' => 'Invalid payment data.']);
    }
}
