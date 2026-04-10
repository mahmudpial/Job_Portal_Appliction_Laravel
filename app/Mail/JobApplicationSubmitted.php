<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application: '.$this->application->job->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-application-submitted',
            with: [
                'application' => $this->application,
                'job' => $this->application->job,
                'company' => $this->application->job->company,
                'applicant' => $this->application->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->application->resume_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->application->resume_path)
                ->as('resume_'.$this->application->user->name.'.'.pathinfo($this->application->resume_path, PATHINFO_EXTENSION));
        }

        return $attachments;
    }
}
