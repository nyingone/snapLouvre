<?php


namespace App\Controller;


use App\Entity\BookingOrder;
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

    /** @var BookingOrder */
    private $bookingOrder;


    /**
     * @Route("/lastcheck", name="lastCheck")
     *
     * @param Request $request
     * @param BookingOrderManager $bookingOrderManager
     * @return Response
     */
    public function index(Request $request, BookingOrderManager $bookingOrderManager): Response
    {
        $this->bookingOrder = $bookingOrderManager->getBookingOrder();
        $form = $this->createForm(BookingValidationType::class, $this->bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()){

            $this->bookingOrder = $form->getData();
            if ($this->bookingOrder->getId() !== null) {
                $bookingOrderManager->save($this->bookingOrder);

            }

           // return $this->redirectToRoute('confirmation');
        }

        return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/charge", name="charge")
     */
    public function charge(\Swift_Mailer $mailer)
    {

        \Stripe\Stripe::setApiKey("sk_test_xxxxxx");

        $token = $_POST['stripeToken'];
        $charge = \Stripe\Charge::create([
            'amount' => $this->bookingOrder->getTotalAmount(),
            'currency' => 'eur',
            'description' => 'Factice payment' . $this->bookingOrder->getBookingRef() ,
            'source' => $token,
        ]);


        return $this->render('inc/_payment.html.twig');
    }
}