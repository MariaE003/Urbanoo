<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentOnReport extends Notification
{
    use Queueable;
    protected $report;
    protected $comment;
    /**
     * Create a new notification instance.
     */
    public function __construct($report,$comment)
    {
        $this->report=$report;
        $this->comment=$comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array //canaux denvoi
    {
        return ['database','mail'];
    }
    public function toDatabase(object $notifiable){
        return[
            'type'=>'comment',
            'report_id'=>$this->report->id,
            'report_title'=>$this->report->title,
            'message'=>'un nouveau commentaire a été  ajoute a votre signalement.',
            'comment_content'=>$this->comment->content,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau commentaire sur votre signalement')
            ->greeting('Bonjour',$notifiable->name. ',')
            ->line('Un nouveau commentaire a été ajoute a votre signalement : "' . $this->report->title . '".')
            ->line('Commentaire : '.$this->comment->content)
            ->action('voir le signalement', url('/'))
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
