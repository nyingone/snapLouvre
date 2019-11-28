<?php

namespace App\Controller;


use App\Entity\Visitor;
use App\Form\BookingOrderType;
use App\Manager\BookingOrderManager;
use App\Manager\Interfaces\BookingOrderManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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

            return $this->redirectToRoute('detail');
        }

        return $this->render('home/index.html.twig', ['bookingOrder' => $bookingOrder,
            'form' => $form->createView(),
        ]);
    }
}

