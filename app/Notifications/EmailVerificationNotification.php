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


    public function via($notifiable): array
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {

        // dd($notifiable->idDoctor);
        $doctor = Doctor::find($notifiable->idDoctor);
        $email = $doctor->email;
        $firstName = $doctor->person->firstName;
        $otp = $this->otp->generate($email, 6, 10); // 6 digits token and 10 min to expired the token
        Log::info('Sending verification email to ' . $email );
        return (new MailMessage)
        ->mailer('smtp')
        ->subject($this->subject)
        ->greeting('Hello' . $firstName)
        ->line($this->message)
        ->line('code: ' . $otp->token);
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
