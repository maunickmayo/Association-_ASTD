<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener
{
  function __invoke(LoginSuccessEvent $event)
  {
    $request = $event->getRequest();
    $username = $event->getPassport()->getUser()->getFirstname();
   
    //dd($username);
    $request->getSession()->getFlashBag()->add('profil', 'Bonjour' . ' ' . $username . ' ' . 'et  bienvenue dans votre espace personnel');

    
  }
}