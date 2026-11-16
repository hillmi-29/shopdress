<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Throwable;

class MergeGuestCartOnLogin
{
    public function __construct(protected CartService $cartService)
    {
    }

    /**
     * Merge the guest cart into the user's cart as soon as authentication succeeds.
     */
    public function handle(Login $event): void
    {
        try {
            $this->cartService->mergeGuestCart($event->user);
        } catch (Throwable $e) {
            // Never block a successful login because of a cart merge issue.
            // The marker is kept, so the merge can be retried on the next cart access.
            Log::error('Failed to merge guest cart after login: '.$e->getMessage(), [
                'user_id' => $event->user->getAuthIdentifier(),
            ]);
        }
    }
}
