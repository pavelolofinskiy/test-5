<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Payments\Contracts\PaymentGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private readonly PaymentGateway $gateway)
    {
    }

    public function __invoke(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_if($order->status !== Order::STATUS_PENDING, 422, 'Order is not pending.');

        $result = $this->gateway->charge($order);

        if (! $result->successful) {
            return response()->json([
                'status' => $result->status,
                'error' => $result->errorMessage,
            ], 502);
        }

        $order->update([
            'status' => Order::STATUS_PAID,
        ]);

        return response()->json([
            'status' => $result->status,
            'gateway_reference' => $result->gatewayReference,
            'checkout_url' => $result->checkoutUrl,
        ]);
    }
}
