<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Valida un código de descuento
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $discount = Discount::where('code', $request->code)->first();

        \Log::info('Discount validation', [
            'code' => $request->code,
            'discount' => $discount,
        ]);

        if ($discount) {
            $isValid =
                $discount->status === 'active' &&
                $discount->usage_limit > $discount->used_count &&
                $discount->expires_at > now();

            if ($isValid) {
                $total = $request->subtotal;
                if ($discount->type === 'percentage') {
                    $total = $total - ($total * $discount->amount) / 100;
                } else {
                    $total = $total - $discount->amount;
                }
            }

            $total = max(0, $total);
            \Log::info('Discount validation result', [
                'valid' => $isValid,
                'discountData' => $discount->only('code', 'type', 'amount'),
                'total' => $total,
            ]);

            return response()->json([
                'valid' => $isValid,
                'discountData' => $discount->only('code', 'type', 'amount'),
                'total' => $total,
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Código de descuento inválido',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //test
        return response()->json(
            [
                'message' => 'Discounts retrieved successfully',
            ],
            200,
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
