<?php


namespace App\Controller;


use App\Entity\BookingOrder;
use App\Services\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

class SendConfirmationMail extends AbstractController
{
    /**
     *  * @Route("/email", name="sendmail")
     * @param EmailService $emailService
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function __invoke(EmailService $emailService)
    {
        return $this->render('notification/email_confirmation.html.twig', ['bookingOrder' => $bookingOrder
        ]);


      /*  $message = (new \Swift_Message('Booking confirmation'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                    'notification/confirmation_email.txt.twig',
                    ['name' => $name]
                )
            );
        $mailer->send($message);
      return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
                'form' => $form->createView(),
                'stripeSession' => $paymentService->setCheckoutSession($this->bookingOrder, 'confirmation','lastCheck'),
                'stripe_public_key' => $paymentService->getPublicKey()
            ]);
      */
    }
}