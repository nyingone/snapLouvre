<?php


namespace App\Controller;


use App\Entity\BookingOrder;
use App\Event\Booking\BookingPlacedEvent;
use App\Form\BookingValidationType;
use App\Manager\BookingOrderManager;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LastCheck extends AbstractController
{
    /** @var BookingOrder */
    private $bookingOrder;

    /**
     * @Route("/lastcheck", name="lastCheck")
     *
     * @param Request $request
     * @param BookingOrderManager $bookingOrderManager
     * @param SessionInterface $session
     * @param PaymentService $paymentService
     * @return Response
     */
    public function __invoke(
        Request $request,
        BookingOrderManager $bookingOrderManager,
        SessionInterface $session,
        PaymentService $paymentService
    ): Response

    {

        $this->bookingOrder = $session->get('BookingOrder');
        $form = $this->createForm(BookingValidationType::class, $this->bookingOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->bookingOrder = $form->getData();
            $bookingOrderManager->place($this->bookingOrder);

            dump($this->bookingOrder);

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