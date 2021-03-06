<?php

namespace App\Controller;

use Twig\Environment;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Contact extends AbstractController
{
    /** @var \Swift_Mailer */
    private $mailer;

    /**
     *
     * public function __Construct(\Swift_Mailer $mailer)
     * {
     * $this->mailer = $mailer;
     * }
     *
     * /**
     * @Route(
     *     path = "/contact",
     *     name="contact",
     *     methods = {"GET"}
     * )
     * @param Request $request
     * @param string $adminEmail
     * @return Response
     */
    public function index(Request $request, string $adminEmail): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $contactFormData = $form->getData();

            $message = (new \Swift_Message('Incoming contact mail'))
                ->setFrom($contactFormData['email'])
                ->setTo($adminEmail)
                ->setBody(
                    $contactFormData['message'],
                    'text/plain'
                );

            $this->mailer->send($message);
            $this->addFlash('info', 'your message has been submitted');
            return $this->redirectToRoute('home');
        }

        return $this->render('contact.html.twig', [ 'form' => $form->createView(),
        ]);
    }

}
