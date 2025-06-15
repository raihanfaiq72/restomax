<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Events\OrderCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $order = DB::transaction(function () use ($validatedData, $request) {
                
                $calculatedAmount = 0;
                $orderItemsData = [];

                foreach ($validatedData['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    $calculatedAmount += $product->price * $item['quantity'];
                    
                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        // Catatan: DBML Anda belum memiliki 'price_per_item' di tabel 'order_items'.
                        // Sangat disarankan untuk menambahkannya agar harga historis tidak berubah.
                        'price_per_item' => $product->price, 
                    ];
                }

                $newOrder = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id' => $request->user()->id, 
                    'customer_id' => $validatedData['customer_id'] ?? null,
                    'table_id' => $validatedData['table_id'] ?? null,
                    'status' => 'pending',
                    'final_amount' => $calculatedAmount, 
                ]);

                $newOrder->order_items()->createMany($orderItemsData);
                
                return $newOrder;
            });

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order->load('order_items.product')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function complete(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be completed.'], 400);
        }

        $order->status = 'completed';
        $order->save();

        OrderCompleted::dispatch($order);

        return response()->json([
            'message' => 'Order completed successfully. Stock reduction process has been triggered.',
            'data' => $order
        ]);
    }

    public function show(Order $order)
    {
        return response()->json([
            'data' => $order->load(['order_items.product', 'customer', 'user'])
        ]);
    }
}
