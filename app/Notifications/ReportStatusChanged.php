<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $report;
    public function __construct($report)
    {
        //
        $this->report=$report;
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
    // stochetr dans db la notif
    public function toDatabase(object $notifiable):array{
        return [
            'report_id' => $this->report->id,
            'title' => $this->report->title,
            'status' => $this->report->status,
            'message' => "Le status de votre report est a ete modifier en :" . $this->report->status,
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise a jour de votre signalement')
            ->greeting("Bonjour " . $notifiable->name . ',')
            ->line('Le statut de votre signalement "' . $this->report->title . '" a ete modifie')
            ->line("nouveau status :". $this->report->status)
            ->action('Voir le signalement', url('/'))
            ->line("Merci d'utiliser notre plateforme");
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
