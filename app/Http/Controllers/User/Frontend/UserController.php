<?php

namespace App\Http\Controllers\User\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Cashier\Cashier;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Laravel\Cashier\Exceptions\IncompletePayment;

class UserController extends Controller
{
    public function chargeUser(Request $request)
    {
        $user = Auth('user')->user();

        try {
            $user->createOrGetStripeCustomer();
            // dd($user);

            $payment = $user->charge(
                $request->input('amount'),
                $request->input('payment_method_id'),
                [
                    'currency' => $request->input('currency')
                ]
            );
            return response()->json(['message' => 'success', 'payment' => $payment], 200);;
        } catch (IncompletePayment $exception) {
            return response()->json(['status' => 'incomplete', 'next' => '3ds', 'client_secret' => $exception->payment->client_secret, 'payment_method' => $exception->payment->payment_method], 500);
        }
    }

    public function unreadNotifications()
    {
        $notifications = auth('user')->user()->unreadNotifications()->take(4)->get();
        return response()->json($notifications, 200);
    }

    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return response()->json(['message' => 'notification read'], 200);
    }

    public function userInfo(User $user)
    {
        return response()->json($user, 200);
    }
}
