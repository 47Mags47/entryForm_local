<?php

namespace App\Mail;

use App\Models\Division;
use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public UserInvite $invite) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invite',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $isUserExists = User::where('email', $this->invite->email)->exists();
        $url = $isUserExists
            ? route('invite-for-user-created.accept', [
                'token' => $this->invite->token,
                'division' => $this->invite->division_id,
            ])
            : route('invites.accept', [
                'token' => $this->invite->token,
            ]);

        return new Content(
            view: 'mails.invite',
            with: [
                'url' => $url,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
