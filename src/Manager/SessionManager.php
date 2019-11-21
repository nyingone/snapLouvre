<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
/** @var SessionInterface */
    private $session;

    private $provisionalRef;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->provisionalRef = $this->session->get('_csrf/customer');

       // dd($this->session);
    }

    public function sessionSet($name = null , $content = null) : void
    {
        if($name !== null || $content !== null){
           $this->session->set($name, $content);
        }
    }

    public function sessionGet(string $name)
    {
        return $this->session->get($name);
    }

    public function getProvisionalRef()
    {
        return $this->provisionalRef;
    }

}