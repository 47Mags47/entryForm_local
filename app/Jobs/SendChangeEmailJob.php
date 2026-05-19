<?php

namespace App\Jobs;

use App\Mail\ChangeEmailMail;
use App\Models\ChangeEmailToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendChangeEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ChangeEmailToken $model)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->model->email)->send(new ChangeEmailMail($this->model));
    }
}
