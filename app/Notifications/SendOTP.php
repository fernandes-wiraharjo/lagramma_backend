<?php

namespace App\Notifications;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SmsMessage;

class SendOTP extends Notification
{
    use Queueable;

    protected $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail'];
        return ['sms'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toSms($notifiable)
    {
        $twilioSid = config('services.twilio.sid');
        $twilioAuthToken = config('services.twilio.token');
        $twilioFrom = config('services.twilio.from');

        $client = new Client($twilioSid, $twilioAuthToken);

        try {
            $client->messages->create(
                $notifiable->phone,
                [
                    'from' => $twilioFrom,
                    'body' => "Your La Gramma Registration OTP code is: {$this->otp}"
                ]
            );
        } catch (\Exception $e) {
            \Log::error("Registration OTP SMS sending failed: " . $e->getMessage());
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
