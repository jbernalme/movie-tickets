<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Module Translations (English)
    |--------------------------------------------------------------------------
    */

    // 🔴 Errores
    'errors' => [
        'title' => 'Payment Error',
        'method_unavailable' => ':method is temporarily unavailable.',
        'method_not_configured' => ':method is not properly configured.',
        'processing_failed' =>
            'We could not process your payment. Please try again.',
        'invalid_amount' => 'The payment amount must be at least :min.',
        'invalid_currency' => 'The selected currency is not supported.',
        'platform_not_found' => 'The selected payment platform was not found.',
        'session_expired' =>
            'Your payment session has expired. Please start over.',
        'approval_failed' =>
            'We were unable to confirm your payment. Please try again.',
        'cancelled' => 'You cancelled the payment.',
        'contact_support' => 'If the problem persists, please contact support.',
        'try_another' => 'Please try with another payment method.',
    ],

    // ✅ Mensajes de éxito
    'messages' => [
        'payment_success' =>
            'Thanks, :name! We received your :amount :currency payment.',
        'payment_saved' => 'Your cart has been saved. You can try again later.',
        'payment_pending' =>
            'Your payment is being processed. You will receive a confirmation email shortly.',
        'payment_refunded' => 'Your refund has been processed successfully.',
    ],

    // 🏷️ Etiquetas de UI
    'labels' => [
        'payment_method' => 'Payment Method',
        'select_method' => 'Select a payment method',
        'amount' => 'Amount',
        'currency' => 'Currency',
        'total' => 'Total',
        'pay_now' => 'Pay Now',
        'pay_with' => 'Pay with :method',
        'processing' => 'Processing...',
        'redirecting' => 'Redirecting to :method...',
    ],

    // 📊 Estados de pago
    'statuses' => [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'approved' => 'Approved',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
        'charged_back' => 'Charged Back',
    ],

    // 💳 Nombres de plataformas (para mostrar al usuario)
    'platforms' => [
        'stripe' => 'Credit/Debit Card',
        'mercadopago' => 'Mercado Pago',
        'paypal' => 'PayPal',
        'transfer' => 'Bank Transfer',
    ],

    // 💰 Monedas
    'currencies' => [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'ARS' => 'Argentine Peso',
        'BRL' => 'Brazilian Real',
        'MXN' => 'Mexican Peso',
        'COP' => 'Colombian Peso',
        'CLP' => 'Chilean Peso',
    ],

    // 📧 Notificaciones por email
    'emails' => [
        'payment_received_subject' => 'Payment Received - :amount :currency',
        'payment_received_greeting' => 'Hello :name,',
        'payment_received_body' =>
            'We have received your payment of :amount :currency. Thank you for your purchase!',
        'payment_failed_subject' => 'Payment Failed',
        'payment_failed_body' =>
            'We were unable to process your payment. Please try again or contact support.',
    ],

    // 🔐 Seguridad y validación
    'security' => [
        'secure_payment' => 'Secure Payment',
        'encrypted' => 'Your payment information is encrypted and secure.',
        'no_store' => 'We do not store your credit card information.',
    ],
];
