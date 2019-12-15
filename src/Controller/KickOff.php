<?php

namespace App\Controller;

use App\Entity\BookingOrder;
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
    /** @var BookingOrder */
    private $bookingOrder;

    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     * @param BookingOrderManagerInterface $bookingOrderManager
     * @return Response
     */
    public function index(Request $request, BookingOrderManagerInterface $bookingOrderManager): Response
    {
        $bookingOrder = $bookingOrderManager->getBookingOrder();
        /** BookingOrder is paid ==> raz and new session vs bokingOrder*/
        if($bookingOrder->getExtPaymentRef() !== null) {
            $bookingOrderManager->razBookingOrder();
            return $this->redirectToRoute('home');
        }

        $savNbvisitors = $bookingOrder->getWishes();

        $form = $this->createForm(BookingOrderType::class, $bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($request->request->get('cancel')) {
                $bookingOrderManager->razBookingOrder();
                return $this->redirectToRoute('home');
            }

            if ($request->request->get('next') && $form->isValid()) {

                $this->bookingOrder = $form->getData();


                if($savNbvisitors > $this->bookingOrder->getWishes()) {
                    $bookingOrderManager->clearVisitors($this->bookingOrder);
                }

                $bookingOrderManager->refreshBookingOrder($this->bookingOrder);

                return $this->redirectToRoute('guest');
            }
        }



        return $this->render('kickOff.html.twig', ['bookingOrder' => $bookingOrder,
            'form' => $form->createView(),

        ]);
    }

}

