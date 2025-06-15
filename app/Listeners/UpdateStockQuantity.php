<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;

class UpdateStockQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;

        foreach ($order->order_items as $item) {
            $product = $item->product;

            if ($product && $product->ingredients) {
                foreach ($product->ingredients as $ingredient) {
                    $quantityToDeduct = $item->quantity * $ingredient->pivot->quantity_needed;

                    $ingredient->decrement('stock_quantity', $quantityToDeduct);

                    if ($ingredient->stock_quantity <= $ingredient->low_stock_threshold) {
                        Log::warning("STOK MENIPIS: {$ingredient->name}. Sisa: {$ingredient->stock_quantity} {$ingredient->unit}");
                    }
                }
            }
        }
    }
}
