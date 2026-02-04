<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherAnnouncement extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Announcement $announcement)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
		return (new MailMessage)
			->subject($this->announcement->subject)
			->greeting("Hello, {$notifiable->name}")
			->line("You have received a new announcement from your teacher: **{$this->announcement->user->name}**")
			->line($this->announcement->description);
    }
}
