<?php

namespace App\Notifications;

use App\Models\Doctor;
use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;


class EmailVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = "use code below : ";
        $this->subject = 'Verivication needed';
        $this->fromEmail = 'azizmahon@gmail.com';
        $this->mailer = 'smtp';
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {

        // dd($notifiable->idDoctor);
        $doctor = Doctor::find($notifiable->idDoctor);
        $email = $doctor->person->email;
        $firstName = $doctor->person->firstName;
        $otp = $this->otp->generate($email, 6, 60); // 60 min 6 digits
        Log::info('Sending verification email to ' . $email );
        return (new MailMessage)
        ->mailer('smtp')
        ->subject($this->subject)
        ->greeting('Hello' . $firstName)
        ->line($this->message)
        ->line('code: ' . $otp->token);
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
