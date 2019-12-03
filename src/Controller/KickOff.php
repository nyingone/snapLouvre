<?php

namespace App\Controller;

use App\Form\BookingOrderType;
use App\Manager\Interfaces\BookingOrderManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KickOff extends AbstractController
{
    /** @var  BookingOrderManagerInterface */
    private $bookingOrderManager;

    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     * @param BookingOrderManagerInterface $bookingOrderManager
     * @return Response
     */
    public function index(Request $request, BookingOrderManagerInterface $bookingOrderManager): Response
    {
        $bookingOrder = $bookingOrderManager->inzBookingOrder();

        $form = $this->createForm(BookingOrderType::class, $bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingOrder = $form->getData();

            $bookingOrderManager->refreshBookingOrder($bookingOrder);

            return $this->redirectToRoute('guest');
        }

        $x = $this->workflow(1);
        return $this->render('kickOff.html.twig', ['bookingOrder' => $bookingOrder,
            'form' => $form->createView(),

        ]);
    }

    public function workflow(int $step) {
        $activ =   " active";
        $textLight = " text-light";

        $x = [];
        return $x;
    }
}

