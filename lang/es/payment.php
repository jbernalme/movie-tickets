<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Módulo de Pagos (Español)
    |--------------------------------------------------------------------------
    */

    // 🔴 Errores
    'errors' => [
        'title' => 'Error de pago',
        'method_unavailable' => ':method no está disponible temporalmente.',
        'method_not_configured' => ':method no está configurado correctamente.',
        'processing_failed' =>
            'No pudimos procesar tu pago. Por favor, intenta de nuevo.',
        'invalid_amount' => 'El monto del pago debe ser al menos :min.',
        'invalid_currency' => 'La moneda seleccionada no es compatible.',
        'platform_not_found' =>
            'La plataforma de pago seleccionada no fue encontrada.',
        'session_expired' =>
            'Tu sesión de pago ha expirado. Por favor, comienza de nuevo.',
        'approval_failed' =>
            'No pudimos confirmar tu pago. Por favor, intenta de nuevo.',
        'cancelled' => 'Has cancelado el pago.',
        'contact_support' => 'Si el problema persiste, contacta a soporte.',
        'try_another' => 'Por favor, intenta con otro método de pago.',
    ],

    // ✅ Mensajes de éxito
    'messages' => [
        'payment_success' =>
            '¡Gracias, :name! Hemos recibido tu pago de :amount :currency.',
        'payment_saved' =>
            'Tu carrito ha sido guardado. Puedes intentar más tarde.',
        'payment_pending' =>
            'Tu pago está siendo procesado. Recibirás un correo de confirmación pronto.',
        'payment_refunded' => 'Tu reembolso ha sido procesado exitosamente.',
    ],

    // 🏷️ Etiquetas de UI
    'labels' => [
        'payment_method' => 'Método de pago',
        'select_method' => 'Selecciona un método de pago',
        'amount' => 'Monto',
        'currency' => 'Moneda',
        'total' => 'Total',
        'pay_now' => 'Pagar ahora',
        'pay_with' => 'Pagar con :method',
        'processing' => 'Procesando...',
        'redirecting' => 'Redirigiendo a :method...',
    ],

    // 📊 Estados de pago
    'statuses' => [
        'pending' => 'Pendiente',
        'processing' => 'Procesando',
        'approved' => 'Aprobado',
        'completed' => 'Completado',
        'failed' => 'Fallido',
        'cancelled' => 'Cancelado',
        'refunded' => 'Reembolsado',
        'charged_back' => 'Contracargo',
    ],

    // 💳 Nombres de plataformas
    'platforms' => [
        'stripe' => 'Tarjeta de Crédito/Débito',
        'mercadopago' => 'Mercado Pago',
        'paypal' => 'PayPal',
        'transfer' => 'Transferencia Bancaria',
    ],

    // 💰 Monedas
    'currencies' => [
        'USD' => 'Dólar Estadounidense',
        'EUR' => 'Euro',
        'ARS' => 'Peso Argentino',
        'BRL' => 'Real Brasileño',
        'MXN' => 'Peso Mexicano',
        'COP' => 'Peso Colombiano',
        'CLP' => 'Peso Chileno',
    ],

    // 📧 Notificaciones por email
    'emails' => [
        'payment_received_subject' => 'Pago recibido - :amount :currency',
        'payment_received_greeting' => 'Hola :name,',
        'payment_received_body' =>
            'Hemos recibido tu pago de :amount :currency. ¡Gracias por tu compra!',
        'payment_failed_subject' => 'Pago fallido',
        'payment_failed_body' =>
            'No pudimos procesar tu pago. Por favor, intenta de nuevo o contacta a soporte.',
    ],

    // 🔐 Seguridad y validación
    'security' => [
        'secure_payment' => 'Pago Seguro',
        'encrypted' => 'Tu información de pago está encriptada y segura.',
        'no_store' => 'No almacenamos tu información de tarjeta de crédito.',
    ],
];
