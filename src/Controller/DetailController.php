<?php


namespace App\Controller;


use App\Form\BookingVisitorsType;
use App\Manager\BookingOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends AbstractController
{
    /**
     * @Route("/detail", name="detail")
     *
     * @param Request $request
     * @param BookingOrderManager $bookingOrderManager
     * @return Response
     */
    public function index(Request $request, BookingOrderManager $bookingOrderManager): Response
    {
        $bookingOrder = $bookingOrderManager->getBookingOrder();
        $form = $this->createForm(BookingVisitorsType::class, $bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()){
            $bookingOrderManager->refreshBookingOrder($bookingOrder);
            return $this->redirectToRoute('recap');
        }


        return $this->render('detail/index.html.twig', ['bookingOrder' => $bookingOrder,
            'form' => $form->createView(),
        ]);
    }
}