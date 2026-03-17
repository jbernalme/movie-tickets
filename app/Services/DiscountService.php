<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lock;

class DiscountService
{
    public function getValidDiscount(string $code): ?Discount
    {
        return Discount::where('code', $code)
            ->where('status', 'active')
            ->whereColumn('usage_limit', '>', 'used_count')
            ->where('expires_at', '>', now())
            ->first();
    }

    public function applyDiscount(Discount $discount, float $subtotal): float
    {
        if ($discount->type === 'percentage') {
            $discountAmount = ($subtotal * $discount->amount) / 100;
            return $subtotal - $discountAmount;
        }

        return $subtotal - $discount->amount;
    }

    /**
     * Valida y consume el descuento (incrementa usos).
     * Usa bloqueo de base de datos para evitar condiciones de carrera.
     *
     * @throws \Exception Si el descuento no es válido o no hay cupo.
     */
    public function consumeDiscount(string $code): Discount
    {
        return DB::transaction(function () use ($code) {
            $discount = Discount::where('code', $code)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            if (!$discount || $discount->usage_limit <= $discount->used_count) {
                throw new \Exception('Código no válido o sin cupo');
            }

            $discount->increment('used_count');

            return $discount;
        });
    }

    /**
     * Helper para formatear el total siempre a 2 decimales.
     */
    public function formatTotal(float $amount): float
    {
        return round(max(0, $amount), 2);
    }
}
