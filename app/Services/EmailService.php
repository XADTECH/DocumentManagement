<?php

namespace App\Services;

use App\Mail\DocumentUploaded;
use Illuminate\Support\Facades\Mail;
use App\Models\Document;

class EmailService
{
    /**
     * Send document uploaded notification emails.
     *
     * @param Document $document
     * @param string $userEmail
     * @param string $adminEmail
     * @param string $documentUrl
     * @return void
     */
    public function sendDocumentUploadedEmails(Document $document, string $userEmail, string $adminEmail, string $documentUrl)
    {
        // Send email to the user
        Mail::to($userEmail)->send(new DocumentUploaded($document, 'user', $documentUrl));

        // Send email to the admin
        Mail::to($adminEmail)->send(new DocumentUploaded($document, 'admin', $documentUrl));
    }
} 