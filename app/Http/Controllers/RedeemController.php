<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Token;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RedeemController extends Controller
{
    /*
    * Handle the redeem request for a product.
    */
    public function store(Product $product): RedirectResponse
    {
        $userId = auth()->id();

        if (! $userId) {
            abort(403);
        }

        DB::transaction(function () use ($product, $userId) {
            $lockedProduct = Product::query()
                ->whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $lockedProduct->is_active) {
                throw ValidationException::withMessages([
                    'redeem' => 'This product is currently unavailable.',
                ]);
            }

            if ((int) $lockedProduct->stock < 1) {
                throw ValidationException::withMessages([
                    'redeem' => 'This product is out of stock.',
                ]);
            }

            $wallet = Token::query()
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            $currentBalance = (int) ($wallet?->balance ?? 0);
            $cost = (int) $lockedProduct->token_cost;

            if ($currentBalance < $cost) {
                throw ValidationException::withMessages([
                    'redeem' => 'You do not have enough tokens for this product.',
                ]);
            }

            $wallet->decrement('balance', $cost);
            $lockedProduct->decrement('stock');

            Order::create([
                'transaction_id' => 'TRX-'.strtoupper(Str::random(10)),
                'user_id' => $userId,
                'product_id' => $lockedProduct->id,
                'tokens_spent' => $cost,
                'status' => 'Pending',
                'tracking_number' => null,
            ]);
        });

        return back()->with('success', 'Redeem request submitted.');
    }
}
