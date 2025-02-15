<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Get the full name of a user by their ID.
     *
     * @param int $userId
     * @return string|null
     */
    public function getUserFullName(int $userId): ?string
    {
        $user = User::find($userId);
        if ($user) {
            return $user->first_name . ' ' . $user->last_name;
        }
        return null;
    }

    /**
     * Send a document uploaded notification email to the user.
     *
     * @param Document $document
     * @param string $userEmail
     * @param string $documentUrl
     * @return void
     */
    public function sendDocumentUploadedEmail($document, string $userEmail, string $documentUrl)
    {
        // Assuming you have a method to send the email
        // You can use the EmailService here if needed
        $emailService = new EmailService();
        $emailService->sendDocumentUploadedEmails($document, $userEmail, 'admin@example.com', $documentUrl);
    }
} 