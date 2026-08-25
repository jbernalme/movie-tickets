<?php

namespace App\Services\PaymentPlatforms;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use App\Models\Payment;
use App\Exceptions\PaymentPlatformNotConfiguredException;

class MercadoPagoService
{
    protected string $accessToken;
    protected string $publicKey;

    public function __construct()
    {
        // ✅ Credenciales desde config/services.php
        $this->accessToken = config('services.mercadopago.access_token');
        $this->publicKey = config('services.mercadopago.public_key');

        // ✅ Inicializar SDK de Mercado Pago
        if (empty($this->accessToken)) {
            throw new PaymentPlatformNotConfiguredException('mercadopago');
        }

        MercadoPagoConfig::setAccessToken($this->accessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    /**
     * ✅ Manejar inicio de pago: crear preferencia y redirigir al checkout
     */
    public function handlePayment(Request $request, Payment $payment)
    {
        try {
            $backUrls = [
                'success' => route('payment.approval', [], true),
                'failure' => route('payment.cancelled', [], true),
                'pending' => route('payment.pending', [], true),
            ];

            Log::info('MercadoPago back_urls', $backUrls);

            $client = new PreferenceClient();

            $preferenceData = [
                'items' => [
                    [
                        'title' => $request->get(
                            'description',
                            'Pago en tu sitio',
                        ),
                        'description' => $request->get('description', ''),
                        'picture_url' => $request->get('image_url'),
                        'category_id' => $request->get('category', 'others'),
                        'quantity' => 1,
                        'unit_price' => (float) $payment->amount,
                        'currency_id' => strtoupper($payment->currency),
                    ],
                ],
                'payer' => [
                    'name' => $request->user()->name ?? null,
                    'email' => $request->user()->email ?? null,
                ],
                'back_urls' => $backUrls,
                'external_reference' => (string) $payment->id, // Para tracking interno
                'statement_descriptor' => config('app.name'), // Nombre en extracto bancario
            ];

            // ✅ Crear preferencia en Mercado Pago
            $preference = $client->create($preferenceData);

            // ✅ Guardar IDs de Mercado Pago en tu BD para tracking
            $payment->update([
                'gateway_id' => $preference->id, // Preference ID
                'status' => 'pending',
                'metadata' => [
                    'preference_id' => $preference->id,
                    'init_point' => $preference->init_point,
                ],
            ]);

            // ✅ Devolver URL del checkout de Mercado Pago
            return $preference->init_point;
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            Log::error('MercadoPago preference creation failed', [
                'payment_id' => $payment->id,
                'status_code' => $e->getStatusCode(),
                'api_response' => $e->getApiResponse()->getContent(),
            ]);

            throw new Exception(
                __('payment.errors.processing_failed') . ' (' . $e->getStatusCode() . ')'
            );
        }
    }

    /**
     * ✅ Manejar aprobación/retorno desde Mercado Pago
     * (cuando el usuario es redirigido de vuelta a tu sitio)
     */
    public function handleApproval(Payment $payment)
    {
        $status = request('status');
        $paymentId = request('payment_id');
        $preferenceId = request('preference_id');

        // ✅ Validar que el preference_id coincide con nuestro registro
        if ($payment->gateway_id !== $preferenceId) {
            Log::warning('MercadoPago preference mismatch', [
                'expected' => $payment->gateway_id,
                'received' => $preferenceId,
            ]);
            return $this->handleFailedPayment($payment);
        }

        try {
            // ✅ Si hay payment_id, consultar estado real para mayor seguridad
            if ($paymentId) {
                $client = new PaymentClient();
                $mpPayment = $client->get($paymentId);
                return $this->processPaymentStatus($payment, $mpPayment);
            }

            // ✅ Fallback: confiar en el status del redirect (menos seguro pero funcional)
            return match ($status) {
                'approved' => $this->handleSuccess($payment),
                'pending' => $this->handlePending($payment),
                default => $this->handleFailedPayment($payment),
            };
        } catch (\Exception $e) {
            Log::error('MercadoPago approval failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return $this->handleFailedPayment($payment);
        }
    }

    /**
     * ✅ Procesar estado real del pago
     */
    protected function processPaymentStatus(Payment $payment, $mpPayment)
    {
        return match ($mpPayment->status) {
            'approved' => $this->handleSuccess($payment, $mpPayment),
            'pending', 'in_process' => $this->handlePending(
                $payment,
                $mpPayment,
            ),
            'rejected',
            'cancelled',
            'charged_back'
                => $this->handleFailedPayment($payment, $mpPayment),
            default => $this->handleFailedPayment($payment, $mpPayment),
        };
    }

    /**
     * ✅ Manejar pago exitoso
     */
    protected function handleSuccess(Payment $payment, $mpPayment = null)
    {
        $payment->update([
            'status' => 'completed',
            'gateway_payment_id' => $mpPayment?->id,
            'paid_at' => now(),
            'metadata' => array_merge($payment->metadata ?? [], [
                'mp_status' => $mpPayment?->status,
                'mp_payment_type' => $mpPayment?->payment_type_id,
                'mp_transaction_amount' => $mpPayment?->transaction_amount,
            ]),
        ]);

        $payment->ticket->update(['status' => 'confirmed']);

        return redirect()
            ->route('dashboard')
            ->withSuccess(
                __('payment.messages.payment_success', [
                    'name' => $payment->ticket->user->name ?? 'Cliente',
                    'amount' => number_format($payment->amount, 2),
                    'currency' => strtoupper($payment->currency),
                ]),
            );
    }

    /**
     * ✅ Manejar pago pendiente (ej: efectivo, ticket)
     */
    protected function handlePending(Payment $payment, $mpPayment = null)
    {
        $payment->update([
            'status' => 'pending',
            'gateway_payment_id' => $mpPayment?->id,
            'metadata' => array_merge($payment->metadata ?? [], [
                'mp_status' => $mpPayment?->status,
                'mp_payment_type' => $mpPayment?->payment_type_id,
            ]),
        ]);

        return redirect()
            ->route('payment.pending', ['payment' => $payment])
            ->withWarning(__('payment.messages.payment_pending'));
    }

    /**
     * ✅ Manejar pago fallido/rechazado
     */
    protected function handleFailedPayment(Payment $payment, $mpPayment = null)
    {
        $payment->update([
            'status' => 'failed',
            'gateway_payment_id' => $mpPayment?->id,
            'failure_reason' => $mpPayment?->status_detail ?? 'unknown',
        ]);

        $payment->ticket->update(['status' => 'cancelled']);

        return redirect()
            ->route('payment.cancelled')
            ->withErrors(['payment' => __('payment.errors.approval_failed')]);
    }

    /**
     * ✅ Obtener Public Key para el frontend (Inertia)
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
