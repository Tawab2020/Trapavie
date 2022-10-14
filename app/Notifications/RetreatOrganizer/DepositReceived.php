<?php

namespace App\Notifications\RetreatOrganizer;

use App\Models\User;
use App\Models\Venue\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositReceived extends Notification implements ShouldQueue
{
    use Queueable;
    protected $booking;
    protected $venue;
    protected $host;
    protected $retreatOrganizer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
        $this->venue = Venue::find($booking['venue_id']);
        $this->host = User::find($this->venue->user_id);
        $this->retreatOrganizer = User::find($booking['user_id']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from($address = 'support@tripavie.com', $name = 'Tripavie Support')
            ->subject('Initial Deposit Received')
            ->view(
                'emails.retreatOrganizer.depositReceived',
                ['booking' => $this->booking, 'host' => $this->host, 'retreatOrganizer' => $this->retreatOrganizer]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $venue_name = $this->booking['venue_log']['name'];
        return [
            "message" => "Your initial deposit to secure the booking for $venue_name has been recieved by Tripavie.",
            "booking_id" => $this->booking['id']
        ];
    }
}