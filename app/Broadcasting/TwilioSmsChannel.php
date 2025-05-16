<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioSmsChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
        //
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $phone = $notifiable->routeNotificationFor('sms', $notification)) {
            return;
        }

        $message = $notification->toSms($notifiable);

        $twilioSid = config('services.twilio.sid');
        $twilioAuthToken = config('services.twilio.token');
        $twilioFrom = config('services.twilio.from');

        $client = new Client($twilioSid, $twilioAuthToken);

        try {
            $client->messages->create($phone, [
                'from' => $twilioFrom,
                'body' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error("Registration OTP SMS sending failed: " . $e->getMessage());
        }
    }
}
