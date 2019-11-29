<?php


namespace App\Controller;


use App\Form\BookingValidationType;
use App\Manager\BookingOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LastCheck extends AbstractController
{
    /**
     * @var BookingOrderManager
     */
    private $bookingOrderManager;


    /**
     * @Route("/lastcheck", name="lastCheck")
     *
     * @param Request $request
     * @param BookingOrderManager $bookingOrderManager
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, BookingOrderManager $bookingOrderManager): Response
    {
        $bookingOrder = $bookingOrderManager->getBookingOrder();

        $form = $this->createForm(BookingValidationType::class, $bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()){
            $this->bookingOrderManager->refreshBookingOrder($bookingOrder);
            return $this->redirectToRoute('payment');
        }

        return $this->render('recap/index.html.twig', ['bookingOrder' => $bookingOrder,
            'form' => $form->createView(),
        ]);
    }
}