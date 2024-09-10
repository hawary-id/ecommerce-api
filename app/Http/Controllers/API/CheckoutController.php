<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Voucher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        try {
            $validated = $request->validated();

            // Validasi user dan voucher
            $user = User::find($validated['user_id']);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $voucher = Voucher::where('code', $validated['voucher_code'])->first();

            $totalPrice = $this->calculateTotalPrice($validated['items']);
            $discount = 0;
            $pointsEarned = 0;

            if ($voucher) {
                $discount = $totalPrice * ($voucher->discount / 100);
                $pointsEarned = $discount * 0.02;
            }

            DB::beginTransaction();

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher ? $voucher->id : null,
                'total_price' => $totalPrice - $discount,
                'discount' => $discount,
                'points_earned' => $pointsEarned
            ]);

            foreach ($validated['items'] as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaction successful!',
                'transaction' => $transaction
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return response()->json(['error' => 'Transaction failed', 'message' => $e->getMessage()], 500);
        }
    }


    private function calculateTotalPrice(array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
