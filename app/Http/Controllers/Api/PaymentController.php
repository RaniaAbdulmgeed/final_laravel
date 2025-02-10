<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;

class PaymentController extends Controller
{
    /**
     * Get all payments.
     */
    public function index()
    {
        $payments = Payment::with(['order', 'user'])->get();
        return PaymentResource::collection($payments);
    }

    /**
     * Get a single payment by ID.
     */
    public function show($id)
    {
        $payment = Payment::with(['order', 'user'])->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return new PaymentResource($payment);
    }

    /**
     * Insert a new payment.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string|in:pending,paid,failed',
            'state' => 'required|integer|in:0,1'
        ]);

        $payment = Payment::create($validatedData);

        return response()->json([
            'message' => 'Payment created successfully',
            'payment' => new PaymentResource($payment)
        ], 201);
    }

    /**
     * Update an existing payment.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string|in:pending,paid,failed',
            'state' => 'required|integer|in:0,1'
        ]);

        $payment->update($validatedData);

        return response()->json([
            'message' => 'Payment updated successfully',
            'payment' => new PaymentResource($payment)
        ]);
    }

    /**
     * Soft delete a payment.
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
