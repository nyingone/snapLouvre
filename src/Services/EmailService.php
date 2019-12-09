<?php


namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailService extends AbstractController
{
public function __construct(EmailService $emailService, string $stripePublicKey)
{

}
    public function __invoke()
    {

    }
}