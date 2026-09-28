<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Construit le mail avec un lien vers Nuxt.
     */
    public function toMail($notifiable): MailMessage
    {
        // URL de ta page Nuxt
        $url = 'http://localhost:3000/reinitialiser-mot-de-passe'
             . '?token=' . $this->token
             . '&email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe FloLiv')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte FloLiv.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line('Ce lien expirera dans 60 minutes.')
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, aucune action n\'est requise.')
            ->salutation('Cordialement, l\'équipe FloLiv');
    }
}