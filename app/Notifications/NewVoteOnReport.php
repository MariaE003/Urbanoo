<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVoteOnReport extends Notification
{
    use Queueable;
    protected $report;
    /**
     * Create a new notification instance.
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable){
        return[
            'type'=>'vote',
            'report_id'=>$this->report->id,
            'report_title'=>$this->report->title,
            'message' => 'Votre signalement a recu un nouveau vote.',
        ];
    } 
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau vote sur votre signalement')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre signalement "' . $this->report->title . '" a recu un nouveau vote.')
            ->action('Voir le signalement', url('/'))
            ->line("Merci d'utiliser notre plateforme.");
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
