<?php

namespace App\Resolvers;

use App\Models\PaymentPlatform;
use App\Exceptions\PaymentPlatformNotConfiguredException;
use Illuminate\Support\Facades\Cache;

class PaymentPlatformResolver
{
    public function resolveService(int $paymentPlatformId)
    {
        // 1. Buscar plataforma específica (no todas)
        $platform = Cache::remember(
            "payment_platform_{$paymentPlatformId}",
            3600,
            fn() => PaymentPlatform::find($paymentPlatformId),
        );

        // 2. Validar que existe
        if (!$platform) {
            throw new PaymentPlatformNotConfiguredException($paymentPlatformId);
        }

        // 3. Obtener clase del config
        $name = strtolower($platform->name);
        $serviceClass = config("services.{$name}.class");

        // 4. Validar que está configurada
        if (!$serviceClass || !class_exists($serviceClass)) {
            throw new PaymentPlatformNotConfiguredException($name);
        }

        // 5. Resolver desde el contenedor
        return resolve($serviceClass);
    }
}
