<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\HttpFoundation\Response;


class EnvoieEmail
{
    private $Mailer;
    public function __construct(MailerInterface $mailer_)
    {
        $this->mailer = $mailer_;
    }

    public function SendEmail(string $email, $subject, $template, $context)
    {
        $contentemail = (new TemplatedEmail())
            ->from('trtrecrutement2022@gmail.com')
            ->to($email)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        $this->mailer->send($contentemail);
    }
    public function SendEmailSimple(string $email, $subject, $template)
    {
        $contentemail = (new TemplatedEmail())
            ->from('trtrecrutement2022@gmail.com')
            ->to($email)
            ->subject($subject)
            ->htmlTemplate($template);


        $this->mailer->send($contentemail);
    }
}
