<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(protected MidtransService $midtransService)
    {
    }

    /**
     * Handle incoming Midtrans HTTP POST notification callback.
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('Midtrans Webhook Received', $request->all());

        $result = $this->midtransService->handleNotification($request->all());

        if ($result['status'] === 'error') {
            return response()->json($result, 400);
        }

        return response()->json($result, 200);
    }
}
