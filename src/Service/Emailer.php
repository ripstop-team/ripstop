<?php namespace Ripstop\Service;

use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Mustache_Engine;

class Emailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Mustache_Engine
     */
    private $mustache;

    /**
     * @var string
     */
    private $template;

    public function __construct(Swift_Mailer $mailer, Mustache_Engine $mustache, string $template = null)
    {
        $this->mailer   = $mailer;
        $this->mustache = $mustache;
        $this->template = $template ?? dirname(__DIR__, 2) . '/templates/email_message.mustache';
    }

    public function __invoke($subject, $recipient, $sender, $attachment, $data): bool
    {
        $subject = $this->mustache->render($subject, $data);
        $body    = $this->mustache->render($this->template, $data);
        $mail    = (new Swift_Message($subject, $body))
            ->setFrom($sender)
            ->setTo($recipient)
            ->attach(Swift_Attachment::fromPath($attachment));
        $result  = $this->mailer->send($mail);

        return $result === 1;
    }
}
