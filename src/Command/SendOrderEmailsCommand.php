<?php

namespace App\Command;

use App\Helpers\OrderTrait;
use App\Manager\EntityManager;
use App\Repository\ContactUsRepository;
use App\Repository\OrderEmailRepository;
use Knp\Snappy\Pdf;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Stopwatch\Stopwatch;

class SendOrderEmailsCommand extends Command
{
    use OrderTrait;

    protected static $defaultName = 'app:send-order-emails';
    protected static $defaultDescription = 'Command to send invoices for newly created orders';

    /** @var ContainerInterface $container */
    private $container;
    /** @var MailerInterface $mailer */
    private $mailer;
    /** @var OrderEmailRepository $orderEmailRepository */
    private $orderEmailRepository;
    /** @var  ContactUsRepository $contactUsRepository */
    private $contactUsRepository;
    /** @var EntityManager $manager */
    private $manager;
    /** @var string $emailFrom */
    private $emailFrom;
    /** @var string $urlFront */
    private $urlFront;
    /** @var string $urlBack */
    private $urlBack;
    /** @var Pdf $pdf */
    private $pdf;

    /**
     * @param ContainerInterface $container
     * @param MailerInterface $mailer
     * @param EntityManager $manager
     * @param OrderEmailRepository $orderEmailRepository
     * @param ContactUsRepository $contactUsRepository
     * @param Pdf $pdf
     * @param $emailFrom
     * @param $urlFront
     * @param $urlBack
     */
    public function __construct(
        ContainerInterface $container,
        MailerInterface $mailer,
        EntityManager $manager,
        OrderEmailRepository $orderEmailRepository,
        ContactUsRepository $contactUsRepository,
        Pdf $pdf,
        $emailFrom,
        $urlFront,
        $urlBack
    ) {
        parent::__construct();

        $this->container = $container;
        $this->mailer = $mailer;
        $this->manager = $manager;
        $this->orderEmailRepository = $orderEmailRepository;
        $this->contactUsRepository = $contactUsRepository;
        $this->emailFrom = $emailFrom;
        $this->urlFront = $urlFront;
        $this->urlBack = $urlBack;
        $this->pdf = $pdf;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stopwatch = new Stopwatch();

        if ($stopwatch->isStarted(self::$defaultName)) {
            return Command::SUCCESS;
        }

        $stopwatch->start(self::$defaultName);

        $orderEmails = $this->orderEmailRepository->findBy([], ['id' => 'ASC']);
        foreach ($orderEmails as $data) {

            try {

                $order = $data->getOrderId();
                [$asArray, $contact] = [$order->asArray(), $this->contactUsRepository->findContact()];

                $html = $this->container->get('twig')->render('order/voucher.html.twig', [
                    'order' => $asArray,
                    'statusPayment' => $this->statusPayment($asArray['paymentMethod'], $asArray['checkoutStatus']),
                    'statusOrder' => $this->statusOrder($asArray['status']),
                    'urlBack' => $this->urlBack,
                ]);

                $footerHtml = $this->container->get('twig')->render('order/includes/footer.html.twig', [
                    'contact' => $contact,
                ]);

                [$toEmail, $toName] = [$order->getCustomerId()->getEmail(), $order->getCustomerId()->getName()];
                $email = (new TemplatedEmail())
                    ->from($this->emailFrom)
                    ->to(new Address($toEmail, $toName))
                    ->subject('Hola '.$toName.', gracias por comprar con nosotros.')

                    // path of the Twig template to render
                    ->htmlTemplate('order/email.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                        'from' => $this->emailFrom,
                        'contact' => $contact,
                        'urlFront' => $this->urlFront,
                        'order' => $asArray,
                    ])
                    ->attach(
                        $this->pdf->getOutputFromHtml($html, ['footer-html' => $footerHtml]),
                        'voucher.pdf',
                        'application/pdf'
                    );

                $this->mailer->send($email);

                $this->manager->remove($data);

            } catch (\Exception | TransportExceptionInterface $ex) {

                $this->manager->save($data->setSendError(true)->setErrorMessage($ex->getMessage()));

            }

        }

        $io->success('Command success.');

        return Command::SUCCESS;
    }
}
