<?php
namespace App\EventListener;

use Twig\Environment;
use App\Entity\Contact\Contact;

class ContactNotification
{
    private $mailer;
    private $renderer;
    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }
    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message('Subject : Bon Plan Message '))
            ->setFrom($contact->getEmail())
            ->setTo('dhiua99@gmail.com')
            ->setBody($this->renderer->render('home/email.html.twig', [
                'contact' => $contact
            ]), 'text/html');
        if ($this->mailer->send($message)) {
            echo '[SWIFTMAILER] sent email to ' . $contact->getEmail();
        } else {
            echo '[SWIFTMAILER] not sending email: ';
        }
    }
}
