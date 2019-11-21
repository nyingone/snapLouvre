<?php

namespace App\Controller;

use Twig\Environment;
use App\Form\BookingOrderType;
use App\Manager\BookingOrderManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    /** @var BookingOrderManager */
    private $bookingOrderManager;

    /** @var SessionInterface */
    private $session;

     /** @var BookingOrder */
        private $bookingOrder;

    public function __Construct(SessionInterface $session, BookingOrderManager $bookingOrderManager)
    {
        $this->session = $session;
        $this->bookingOrderManager = $bookingOrderManager;
    }

    /**
     * @Route(
     *    path = "/",
     *    name="home",
     *    methods  = {"GET", "POST"}
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->bookingOrder= $this->bookingOrderManager->inzBookingOrder();

        $form = $this->createForm(BookingOrderType::class, $this->bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $this->bookingOrder  = $form->getData();

            $this->bookingOrderManager->refreshBookingOrder($this->bookingOrder);

            if($this->session->get('booking_error')):
               //  dd($this->session->get('bookingOrder_error'), $this->session->get('visitor_error'), $this->customer );
            else:
                return $this->redirectToRoute('confirmation');
            endif;
        }


        return $this->render('home/index.html.twig', [ 'bookingOrder' => $this->bookingOrder,
                'form' => $form->createView(),
                ]);
    }

  }

