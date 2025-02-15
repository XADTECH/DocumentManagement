<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class DocumentUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $templateType;
    public $documentUrl;

    /**
     * Create a new message instance.
     *
     * @param Document $document
     * @param string $templateType
     * @return void
     */
    public function __construct(Document $document, $templateType = 'user', $documentUrl)
    {
        $this->document = $document;
        $this->templateType = $templateType;
        $this->documentUrl = $documentUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $uploadedBy = User::where('id', $this->document->uploaded_by)->first()->first_name . ' ' . User::where('id', $this->document->uploaded_by)->first()->last_name;
        return $this->view( 'emails.document_uploaded')
                    ->subject('Document Uploaded Notification')
                    ->with([
                        'documentName' => $this->document->name,
                        'uploadedBy' => $uploadedBy,
                        'documentUrl' => $this->documentUrl,
                    ]);
    }
} 