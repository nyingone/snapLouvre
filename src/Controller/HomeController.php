<?php

namespace App\Controller;


use Twig\Environment;
use App\Manager\BookingOrderManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController
{
    /** @var Customer */
    private $customer;

/** @var BookingOrderManager */
    private $bookingOrderManager;

    /** @var SessionInterface */
        private $session;

    public function __Construct(SessionInterface $session, BookingOrderManager $bookingOrderManager)
    {
        $this->session = $session;
        $this->bookingOrderManager = $bookingOrderManager;
        $this->bookingOrder= $bookingOrderManager->inzBookingOrder();
    }

    /**
     * @Route("/", name="home")
     * @Method{"GET"}
     */
    public function index(Request $request)
    {
        $form = $this->createForm(HomeType::class, $this->bookingOrder);
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

        return $this->render('home/index.html.twig', [ 'form' => $form->createView(),
        ]);
    }

}
