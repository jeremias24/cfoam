<?php

namespace App\Services;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Arr;

class MailService extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $content;

    /**
     * Create a new message instance.
     */
    public function __construct($content)
    {
        
        $this->content = $content;

    }

    /**
     * Build the message.
     * 
     * @return $this
     */
    public function build()
    {

        $content = $this->from($address = $this->content->sender, $name = config('app.name'))
            ->to($this->content->to)
            ->subject($this->content->subject)
            ->view($this->content->template)
            ->with('data', $this->content->data);

        if (isset($this->content->attachments)) {
            foreach($this->content->attachments as $attachment) {
                $content->attach(public_path($attachment->filepath . '/' . $attachment->filename), [
                    'as' => $attachment->filename,
                    'mime' => $attachment->mime_type,
                ]);
            }
        }

        if (collect($this->content)->has('attachmentDatas')) {
            foreach($this->content->attachmentDatas as $attachmentData)
                $content->attachData($attachmentData['output'] , $attachmentData['filename']);
        }

        return  $content;

    }
    
}
