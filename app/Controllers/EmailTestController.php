<?php
namespace App\Controllers;

use App\Emails\EmailHandler;

class EmailTestController
    {
        private $emailHandler;

           public function __construct()
        {
            $this->emailHandler = new EmailHandler();
        }

        public function sendEmail($email)
        {
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => 'error', 'message' => 'Invalid email address'];
            }

            // Simulate sending email
            $name = "User"; // You can modify this to get the actual user's name
            $sent = $this->sendTest($email, $name);

           return $sent;
        }

        private function sendTest(string $email, string $name): bool
        {
            $subject    = "This is just a test email";
            $templateId = 'x2p0347y32y4zdrn';

            // Example data to pass to the template
            $personalization = [
                'USERNAME' => $name,
                'STAY_HAPPY_URL' => env_var('STAY_HAPPY_URL'),
            ];

            $attachmentPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'Nehemiah Angana Resume 01.pdf';

             if (!file_exists($attachmentPath)) {
                throw new \Exception("Attachment file not found: {$attachmentPath}");
            }

            // Attachments: Ensure valid file path
           $attachments = [$attachmentPath];

            // Call the sendEmail method from EmailFunctions
            $result = $this->emailHandler->sendEmail(
                $email,                // recipient
                $subject,              // subject
                $templateId,           // template ID
                $personalization,      // personalization array
                $attachments           // attachments array
            );

            if ($result === true) {
                echo "Email sent successfully to {$email} ✅\n";
                return true;
            } else {
                echo "Failed to send email: {$result} ❌\n";
                return false;
            }
        }

    }
