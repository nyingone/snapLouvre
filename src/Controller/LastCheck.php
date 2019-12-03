<?php


namespace App\Controller;


use App\Entity\BookingOrder;
use App\Form\BookingValidationType;
use App\Manager\BookingOrderManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

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
    public function index(Request $request, BookingOrderManager $bookingOrderManager, RouterInterface $router): Response
    {
        $this->bookingOrder = $bookingOrderManager->getBookingOrder();
        $form = $this->createForm(BookingValidationType::class, $this->bookingOrder);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->bookingOrder = $form->getData();
            $bookingOrderManager->save($this->bookingOrder);


            return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
                'form' => $form->createView(),
                'stripeSession' => $this->setCheckoutSession($router)
            ]);

        }

        return $this->render('lastCheck.html.twig', ['bookingOrder' => $this->bookingOrder,
            'form' => $form->createView(),
        ]);
    }

    public function setCheckoutSession(\Symfony\Component\Routing\RouterInterface $router)
    {


        \Stripe\Stripe::setApiKey('sk_test_yoj8qJgNDQkA6UWC2tuTzndK00hMdJ6t6C');

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $this->bookingOrder->getCustomer()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'SnapLouvre tickets',
                'description' => $this->bookingOrder->getBookingRef(),
                'amount' => $this->bookingOrder->getTotalAmount()*100,
                'currency' => 'eur',
                'quantity' => $this->bookingOrder->getWishes(),
            ]],
            'success_url' => $router->generate('confirmation',[], RouterInterface::ABSOLUTE_URL).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $router->generate('lastCheck',[], RouterInterface::ABSOLUTE_URL),
        ]);

        dump($session);

        return $session;


    }


}