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

    public function __construct(Swift_Mailer $mailer, Mustache_Engine $mustache, string $template)
    {
        $this->mailer   = $mailer;
        $this->mustache = $mustache;
        $this->template = $template;
    }

    public function __invoke($subject, $recipient, $sender, $attachment, $data): bool
    {
        $subject = $this->mustache->render($subject, $data);
        $body    = $this->mustache->render(file_get_contents($this->template), $data);

        $mail   = (new Swift_Message($subject, $body))
            ->setFrom($sender)
            ->setTo($recipient)
            ->attach(Swift_Attachment::fromPath($attachment));
        $result = $this->mailer->send($mail);

        return $result === 1;
    }
}
