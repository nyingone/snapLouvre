<?php

namespace App\Controller;


use App\Controller\Interfaces\PaymentAuthenticate;
use App\Manager\BookingOrderManager;
use App\Manager\SessionManager;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Confirmation extends AbstractController implements PaymentAuthenticate
{

    /**
     * @Route("/confirmation{id<\/\w+>?}", name="confirmation")
     * @param $id
     * @param BookingOrderManager $bookingOrderManager
     * @return Response
     */
    public function invoke($id, BookingOrderManager $bookingOrderManager) : Response
    {
        $bookingOrder = $bookingOrderManager->getBookingOrder();
        dump($id);
        dump( $bookingOrder);

        return $this->render('confirmation.html.twig', ['bookingOrder' => $bookingOrder,
        ]);
    }
}