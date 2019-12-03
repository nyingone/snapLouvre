<?php

namespace App\Controller;


use App\Manager\BookingOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Confirmation extends AbstractController
{

    /**
     * @Route("/confirmation", name="confirmation")
     * @param BookingOrderManager $bookingOrderManager
     * @return Response
     */
    public function index(BookingOrderManager $bookingOrderManager) : Response
    {
        $bookingOrder = $bookingOrderManager->getBookingOrder();
        return $this->render('confirmation.html.twig', ['bookingOrder' => $bookingOrder,
        ]);

    }
}