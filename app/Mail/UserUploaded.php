<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class UserUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $templateType;
    public $documentUrl;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param Document $document
     * @param string $templateType
     * @return void
     */
    public function __construct(Document $document, $templateType = 'user', $documentUrl, $status )
    {
        $this->document = $document;
        $this->templateType = $templateType;
        $this->documentUrl = $documentUrl;
        $this->status = $status;
    }
//upload status
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $uploadedBy = User::where('id', $this->document->uploaded_by)->first()->first_name . ' ' . User::where('id', $this->document->uploaded_by)->first()->last_name;
        return $this->view( 'emails.document_uploaded_user')
                    ->subject('Document Uploaded Notification')
                    ->with([
                        'documentName' => $this->document->name,
                        'uploadedBy' => $uploadedBy,
                        'documentUrl' => $this->documentUrl,
                        'status' => $this->status,
                    ]);
    }
} 