<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Get all orders.
     */
    public function index()
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    /**
     * Get a single order by ID.
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }

    /**
     * Insert a new order.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'state' => 'required|integer|in:0,1'
        ]);


        $order = Order::create($validatedData);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order)
        ], 201);
    }

    /**
     * Update an existing order.
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'state' => 'required|integer|in:0,1'
        ]);


        $order->update($validatedData);

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => new OrderResource($order)
        ]);
    }

    /**
     * Soft delete an order.
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
