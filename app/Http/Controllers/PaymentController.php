<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ticket;
use App\Services\PaymentPlatforms\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService,
    ) {}

    public function process(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $ticket = Ticket::where('id', $validated['ticket_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status !== 'pending') {
            return back()->withErrors(['ticket' => 'El ticket no está pendiente de pago.']);
        }

        $payment = Payment::create([
            'ticket_id' => $ticket->id,
            'amount' => $ticket->final_price,
            'currency' => config('services.mercadopago.base_currency', 'cop'),
            'payment_method' => 'mercadopago',
            'status' => 'pending',
        ]);

        try {
            $initPoint = $this->mercadoPagoService->handlePayment($request, $payment);

            return response()->json(['init_point' => $initPoint]);
        } catch (\Exception $e) {
            Log::error('Payment process failed', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticket->id,
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function approval(Request $request)
    {
        $payment = $this->findPaymentFromRequest($request);

        if (!$payment) {
            return redirect()->route('dashboard')
                ->withErrors(['payment' => 'No se encontró el pago.']);
        }

        return $this->mercadoPagoService->handleApproval($payment);
    }

    public function cancelled(Request $request)
    {
        $payment = $this->findPaymentFromRequest($request);

        if ($payment && $payment->status === 'pending') {
            $payment->update(['status' => 'failed', 'failure_reason' => 'user_cancelled']);
        }

        return Inertia::render('payment/cancelled', [
            'payment' => $payment,
        ]);
    }

    public function pending(Request $request)
    {
        $payment = $this->findPaymentFromRequest($request);

        return Inertia::render('payment/pending', [
            'payment' => $payment,
        ]);
    }

    private function findPaymentFromRequest(Request $request): ?Payment
    {
        $externalReference = $request->query('external_reference');

        if ($externalReference) {
            return Payment::find($externalReference);
        }

        $preferenceId = $request->query('preference_id');

        if ($preferenceId) {
            return Payment::where('gateway_id', $preferenceId)->first();
        }

        return null;
    }
}
