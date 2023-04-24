<?php

namespace Sarfraznawaz2005\LaraFeed\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Sarfraznawaz2005\LaraFeed\Models\LaraFeedModel;

class LaraFeedNotification extends Notification
{
    use Queueable;

    public $feedback = null;
    public $attachement = null;


    /**
     * Create a new notification instance.
     *
     * @param LaraFeedModel $feedback
     */
    public function __construct(LaraFeedModel $feedback, $file)
    {
        $this->feedback = $feedback;
        $this->attachement = $file;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage;
        $mailMessage->subject(config('larafeed.mail.mail_subject', 'New Feedback Received'));
        $mailMessage->greeting(config('larafeed.mail.mail_subject', 'New Feedback Received'));

        $mailMessage->line('Name : ' . $this->feedback->name);
        $mailMessage->line('Email : ' . $this->feedback->email);
        $mailMessage->line('Message : ' . $this->feedback->message);
        $mailMessage->line('IP : ' . $this->feedback->ip);
        $mailMessage->line('URL : ' . $this->feedback->uri);
        $mailMessage->line('Date : ' . $this->feedback->created_at);

        if (config('larafeed.screenshots.capture_screenshots', true)) {
            $outputPath = config('larafeed.screenshots.screenshots_store_folder');
            
            $mailMessage->attach($outputPath . DIRECTORY_SEPARATOR . $this->feedback->screenshot);
        }

        if($this->attachement) {
            $mailMessage->attach($this->attachement, [
                'as' => $this->attachement->getClientOriginalName(),
                'mime' => $this->attachement->getMimeType(),
            ]);
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
