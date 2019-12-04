<?php


namespace App\Controller;


use App\Entity\BookingOrder;
use App\Form\BookingValidationType;
use App\Manager\BookingOrderManager;
use App\Services\PaymentService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param PaymentService $paymentService
     * @return Response
     */
    public function index(Request $request, BookingOrderManager $bookingOrderManager, PaymentService $paymentService): Response
    {
        $this->bookingOrder = $bookingOrderManager->getBookingOrder();
        $form = $this->createForm(BookingValidationType::class, $this->bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingOrder = $form->getData();
            $bookingOrderManager->save($this->bookingOrder);

            return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
                'form' => $form->createView(),
                'stripeSession' => $paymentService->setCheckoutSession($this->bookingOrder, 'confirmation','lastCheck'),
                'stripe_public_key' => $paymentService->getPublicKey()
            ]);

        }

        return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
            'form' => $form->createView(),
        ]);
    }




}