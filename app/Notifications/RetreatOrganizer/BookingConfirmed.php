<?php

namespace App\Notifications\RetreatOrganizer;

use App\Models\User;
use App\Models\Venue\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification implements ShouldQueue
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
            ->subject('Booking Request Confirmed')
            ->view(
                'emails.retreatOrganizer.bookingConfirmed',
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
            "message" => "Your booking request for $venue_name has been confirmed by the venue host. You may now proceed with the payment.",
            "booking_id" => $this->booking['id']
        ];
    }
}
