<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['product'])->get(),200);
    }

    public function deliverOrder(Order $order)
    {
        $order->is_delivered = true;
        $status = $order->save();

        return response()->json([
            'status'    => $status,
            'data'      => $order,
            'message'   => $status ? 'Order Delivered!' : 'Error Delivering Order'
        ]);
    }

    public function store(Request $request)
    {

        $user = User::find(Auth::id());

        try {

            $payment = $user->charge(
                $request->input('amount'),
                $request->input('payment_method_id')
            );

            $payment = $payment->asStripePaymentIntent();

            $order = Order::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'address' => $request->address
            ]);

            return $order;

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function show(Order $order)
    {
        return response()->json($order,200);
    }

    public function update(Request $request, Order $order)
    {
        $status = $order->update(
            $request->only(['quantity'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Order Updated!' : 'Error Updating Order'
        ]);
    }

    public function destroy(Order $order)
    {
        $status = $order->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Order Deleted!' : 'Error Deleting Order'
        ]);
    }
}
