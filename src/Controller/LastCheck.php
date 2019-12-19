<?php


namespace App\Controller;


use App\Entity\BookingOrder;
use App\Form\BookingValidationType;
use App\Manager\BookingOrderManager;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


class LastCheck extends AbstractController
{
    /** @var BookingOrder */
    private $bookingOrder;

    /**
     * @Route("/lastcheck", name="lastCheck")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param BookingOrderManager $bookingOrderManager
     * @param SessionInterface $session
     * @param PaymentService $paymentService
     * @return Response
     */
    public function __invoke(
        Request $request,
        TranslatorInterface $translator,
        BookingOrderManager $bookingOrderManager,
        SessionInterface $session,
        PaymentService $paymentService
    ): Response

    {

        $this->bookingOrder = $session->get('BookingOrder');
        $form = $this->createForm(BookingValidationType::class, $this->bookingOrder);
        $form->handleRequest($request);

        if ($request->request->get('cancel')) {
            if ($this->bookingOrder->getSettledAt() !== null) {
                return $this->redirectToRoute('home');
            }

            return $this->redirectToRoute('guest');
        }

        if ($this->bookingOrder->getValidatedAt() !== null && $this->bookingOrder->getSettledAt() == null) {

            $this->addFlash('warning', $translator->trans('no_returned_payment'));
        }

        if ($form->isSubmitted()) {

            if ($request->request->get('next') && $form->isValid()) {
                $this->bookingOrder = $form->getData();
                $bookingOrderManager->place($this->bookingOrder);

                return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
                    'form' => $form->createView(),
                    'stripeSession' => $paymentService->setCheckoutSession($this->bookingOrder, 'confirmation', 'lastCheck'),
                    'stripe_public_key' => $paymentService->getPublicKey()
                ]);
            }
            if ($request->request->get('next') && !$form->isValid()){
                $this->addFlash('warning', $translator->trans('booking_failed_click_on_first_item_of_follow_reservation_to change_date'));
            }

        }

        return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
            'form' => $form->createView(),
        ]);
    }


}