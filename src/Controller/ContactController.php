<?php

namespace App\Controller;

use Twig\Environment;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ContactController extends AbstractController
{
    /** @var \Swift_Mailer */
    private $mailer;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __Construct(\Swift_Mailer $mailer, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->session = $session;
    }

    /**
     * @Route(
     *     path = "/contact",
     *     name="contact",
     *     methods = {"GET"}
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $contactFormData = $form->getData();

            $message = (new \Swift_Message('Incoming contact mail'))
                ->setFrom($contactFormData['email'])
                ->setTo('ycitynil@gmail.com')
                ->setBody(
                    $contactFormData['message'],
                    'text/plain'
                );

            $this->mailer->send($message);

            $this->session->getFlashBag()->add('info', 'your message has been submitted');

        }

        return $this->render('contact/index.html.twig', [ 'form' => $form->createView(),
        ]);
    }

}
