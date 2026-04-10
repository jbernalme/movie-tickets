<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentPlatformNotConfiguredException extends Exception
{
    protected string $platform;

    public function __construct(string $platform, int $code = 503)
    {
        $this->platform = $platform;

        // ✅ Mensaje técnico interno (para logs)
        $technicalMessage = "Payment platform '{$platform}' is not properly configured.";

        parent::__construct($technicalMessage, $code);
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * ✅ Mensaje amigable para el usuario (traducible)
     */
    public function getUserMessage(): string
    {
        return __('payment.errors.method_unavailable', [
            'method' => $this->getPlatformDisplayName(),
        ]);
    }

    /**
     * ✅ Nombre amigable de la plataforma para mostrar al usuario
     */
    protected function getPlatformDisplayName(): string
    {
        return match (strtolower($this->platform)) {
            'mercadopago' => 'Mercado Pago',
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            default => ucfirst($this->platform),
        };
    }

    /**
     * ✅ Renderizado correcto para Inertia
     */
    public function render(Request $request)
    {
        // ✅ Log interno con detalles técnicos (solo para el equipo)
        Log::warning('Payment platform misconfiguration', [
            'platform' => $this->platform,
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ Diferenciar entre petición JSON/API vs navegación normal
        if ($request->expectsJson() || $request->inertia()) {
            // Para Inertia: retornar errores via sesión (se comparten automáticamente como props)
            return back()
                ->withErrors(['payment_method' => $this->getUserMessage()])
                ->withInput();
        }

        // Fallback para requests no-Inertia (raro, pero por seguridad)
        return Inertia::render('Errors/PaymentConfig', [
            'status' => $this->getCode(),
            'title' => __('payment.errors.title'),
            'message' => $this->getUserMessage(),
            'support' => __('payment.errors.contact_support'),
        ]);
    }
}
