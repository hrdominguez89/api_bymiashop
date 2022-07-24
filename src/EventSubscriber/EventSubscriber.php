<?php

namespace App\EventSubscriber;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Class EventSubscriber
 * @package App\EventSubscriber
 * @author Yunior Pantoja Guerrero <ypguerrero123@gmail.com>
 */
class EventSubscriber implements EventSubscriberInterface
{

    /** @var string */
    private $emailFrom;
    /** @var MailerInterface */
    private $mailer;

    /**
     * @param string $emailFrom
     * @param MailerInterface $mailer
     */
    public function __construct(string $emailFrom, MailerInterface $mailer)
    {
        $this->emailFrom = $emailFrom;
        $this->mailer = $mailer;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            #EMAIL
            'api.executed.sendemail' => 'onEmailExecuted',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onEmailExecuted(GenericEvent $event)
    {
        $params = $event->getSubject();

        try {

            $body = 'Hola '.$params['name'].' su solicitud de visa con código '.$params['code'].' ha cambiado de estado. Estado actual: ';
            $this->sendEmail('Cambio de Estado en la solicitud de visa', $params['email'], $body, $params['status']);

        } catch (\Exception $ex) {
        }

    }

    /**
     * @param $subject
     * @param $emailTo
     * @param $body
     * @param string $status
     */
    private function sendEmail($subject, $emailTo, $body, string $status = '')
    {
        try {

            $email = (new TemplatedEmail())
                ->from(new Address($this->emailFrom))
                ->to(new Address($emailTo))
                ->subject($subject)
                // path of the Twig template to render
                ->htmlTemplate('emails/index.html.twig')
                // pass variables (name => value) to the template
                ->context(
                    [
                        'body' => $body,
                        'subject' => $subject,
                        'status' => $status,
                    ]
                );

            $this->mailer->send($email);

        } catch (\Exception | TransportExceptionInterface $exception) {
        }

    }


}
