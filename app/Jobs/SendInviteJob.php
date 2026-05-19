<?php

namespace App\Jobs;

use App\Mail\InviteMail;
use App\Models\UserInvite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInviteJob implements ShouldQueue
{
    use Queueable;

    private $token;

    /**
     * Create a new job instance.
     */
    public function __construct(public UserInvite $invite){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->invite->email)->send(new InviteMail($this->invite));
    }
}
