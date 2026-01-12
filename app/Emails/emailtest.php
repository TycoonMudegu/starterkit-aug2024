<?php
    require_once 'vendor/autoload.php';


        use MailerSend\MailerSend;
        use MailerSend\Helpers\Builder\Recipient;
        use MailerSend\Helpers\Builder\EmailParams;
        use MailerSend\Helpers\Builder\Attachment;
        use MailerSend\Helpers\Builder\Personalization;

        $mailersend = new MailerSend(['api_key' => 'mlsn.fd5da0079068dcc67d5f87feaed40b30dcb702743d1e400f2bd5e811dac978c4']);

            $recipients = [
                new Recipient('tycoonmudegu@gmail.com', ''),
            ];
            $tags = ['tag'];

            $personalization = [
            new Personalization('tycoonmudegu@gmail.com', [
                    'USERNAME' => 'Just a username',
                    'STAY_HAPPY_URL' => 'http://localhost/starterkit-aug2024/StayHappy',
            ])
            ];
                $attachments = [
                    new Attachment(file_get_contents('app/assets/docs/Nehemiah Angana Resume 01.pdf'), 'attachment.jpg')
                ];

            $emailParams = (new EmailParams())
            ->setFrom('test@test-86org8e50v1gew13.mlsender.net')
            ->setFromName('xb67')
            ->setRecipients($recipients)
            ->setSubject('Subject')
            ->setTemplateId('x2p0347y32y4zdrn')
            ->setTags($tags)
            ->setAttachments($attachments)
            ->setPersonalization($personalization);

            $mailersend->email->send($emailParams);
                    echo "Email sent successfully!";
            ?>