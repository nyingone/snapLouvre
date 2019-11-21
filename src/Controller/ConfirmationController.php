<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Type\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
   /** @var Customer */
    private $customer;
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function index(Request $request) : Response
    {
        $form = $this->createForm(CustomerType::class, $this->customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // TODO
        }

        return $this->render('confirmation/index.html.twig', [ 'form' => $form->createView(),
        ]);
    }
}