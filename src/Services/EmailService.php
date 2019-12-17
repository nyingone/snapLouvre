<?php


namespace App\Services;

use App\Entity\BookingOrder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService extends AbstractController
{

    /**
     * @var \Twig\Environment
     */
    private $template;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $sender;
    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * EmailService constructor.
     * @param \Swift_Mailer $mailer
     * @param string $adminEmail
     * @param \Twig\Environment $template
     * @param TranslatorInterface $translator
     */

    public function __construct(\Swift_Mailer $mailer, string $adminEmail, \Twig\Environment $template, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->sender = $adminEmail;
        $this->template = $template;
        $this->translator = $translator;
    }

    /**
     * @param BookingOrder $bookingOrder
     * @return int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendConfirmation(BookingOrder $bookingOrder):int
    {

        $vueName = 'notification/email_confirmation.html.twig';

        $message = (new \Swift_Message())
            ->setSubject($this->translator->trans('subject_bookingOrder_confirmation'))
            ->setFrom($this->sender)
            ->setTo($bookingOrder->getCustomer()->getEmail());

        $img = $message->embed(\Swift_Image::fromPath('img/logo.png'));

        $message->setBody(
            $this->template->render(
                $vueName,
                ['bookingOrder' => $bookingOrder, 'img' => $img]
            ),
            'text/html'
        )
            ->addPart(
                $this->renderView(
                    $vueName,
                    ['bookingOrder' => $bookingOrder, 'img' => $img]
                ),
                'text/plain'
            );
        return $this->mailer->send($message);
    }
}