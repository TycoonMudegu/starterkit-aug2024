<?php

namespace App\Emails;  

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Attachment;
use MailerSend\Helpers\Builder\Personalization;
use Exception;

class EmailHandler {
    protected $mailersend;

    public function __construct()
    {
        $this->mailersend = new MailerSend([
            'api_key' => env_var('MAILERSEND_API_KEY'),
        ]);
    }

    /**
     * Send an email using a Twig template via MailerSend.
     *
     * @param string $recipient        Email address of the recipient.
     * @param string $subject          Subject of the email.
     * @param string $templateId       MailerSend template ID.
     * @param array  $personalization  Key-value pairs for personalization (e.g. ['USERNAME' => 'John']).
     * @param array  $attachments      Array of file paths to attach.
     * @return bool|string             True on success, or the error message string on failure.
     */
    public function sendEmail($recipient, $subject, $templateId, array $personalization = [], array $attachments = [])
    {
        try {
            // Prepare recipients
            $recipients = [
                new Recipient($recipient, ''),
            ];

            // Prepare personalization
            $personalizationData = [
                new Personalization($recipient, $personalization),
            ];

            // Prepare attachments
            $preparedAttachments = [];
            foreach ($attachments as $path) {
                if (file_exists($path)) {
                    $preparedAttachments[] = new Attachment(
                        file_get_contents($path),
                        basename($path)
                    );
                }
            }

            // Build EmailParams
            $emailParams = (new EmailParams())
                ->setFrom(env_var('MAILERSEND_FROM_EMAIL'))
                ->setFromName(env_var('MAILERSEND_FROM_NAME'))
                ->setRecipients($recipients)
                ->setSubject($subject)
                ->setTemplateId($templateId)
                ->setPersonalization($personalizationData)
                ->setAttachments($preparedAttachments);

            // Send email
            $this->mailersend->email->send($emailParams);

            return true; // Return true on success
        } catch (Exception $e) {
            return "Failed to send email to {$recipient}. Error: " . $e->getMessage(); // Return the error message on failure
        }
    }
}
?>
