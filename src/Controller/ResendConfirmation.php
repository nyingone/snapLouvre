<?php


namespace App\Controller;


use App\Entity\BookingOrder;
use App\Manager\BookingOrderManager;
use App\Services\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\Routing\Annotation\Route;

class ResendConfirmation extends AbstractController
{
    /**
     * @Route("/resendMail", name="resendMail")
     * @param BookingOrderManager $bookingOrderManager
     * @param EmailService $emailService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function resendConfirmation(BookingOrderManager $bookingOrderManager, EmailService $emailService)
    {
        //TODO    probl try catch
        $bookingOrder = $bookingOrderManager->getBookingOrder();
        $response = 0;
        $response = 0;

            $response = $emailService->sendConfirmation($bookingOrder);

        if ($response == 1) {
            $bookingOrderManager->confirmOrderSent($bookingOrder);
         // TODO   clean on exit   $emailService->unlinkQrCode($bookingOrder);
        }else{
            dump('sending mail failed');
        }

        return $this->redirectToRoute('confirmation');
    }
}