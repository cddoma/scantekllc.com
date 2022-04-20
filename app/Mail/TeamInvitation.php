<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use App\Models\TeamInvitation as TeamInvitationModel;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The team invitation instance.
     *
     * @var \Laravel\Jetstream\TeamInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \Laravel\Jetstream\TeamInvitation  $invitation
     * @return void
     */
    public function __construct(TeamInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->invitation->team->name . ' invites you to join SCANTEK';
        return $this->markdown('jetstream::mail.team-invitation', ['acceptUrl' => URL::signedRoute('team-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject($subject);
    }
}
