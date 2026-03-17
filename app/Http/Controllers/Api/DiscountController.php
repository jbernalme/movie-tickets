<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Services\DiscountService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct(private DiscountService $discountService) {}

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
            'subtotal' => 'required|numeric|min:0',
        ]);

        $validDiscount = $this->discountService->getValidDiscount(
            $request->code,
        );

        if (!$validDiscount) {
            return response()->json(
                [
                    'valid' => false,
                    'message' => 'Código de descuento inválido o expirado',
                ],
                400,
            );
        }

        // Calcular total
        $total = $this->discountService->applyDiscount(
            $validDiscount,
            $request->subtotal,
        );

        $total = $this->discountService->formatTotal($total);

        return response()->json([
            'valid' => true,
            'discountData' => $validDiscount->only('code', 'type', 'amount'),
            'subtotal' => $request->subtotal,
            'discount_amount' => round($request->subtotal - $total, 2),
            'total' => round($total, 2),
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
