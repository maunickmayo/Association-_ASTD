<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contactez-nous", name="app_contact")
     */
    public function ContactUs(Request $request, MailerInterface $mailer): Response
    {   
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
           
         if ($form->isSubmitted() && $form->isValid()) {
             
            $data = $form->getData();
            $email = (neW TemplatedEmail())
                 ->from($data['email']) 
                 ->to('infoastd@as-st-thomas-diaspora.org') 
                 ->subject('Demande de contact')
                 ->htmlTemplate('email/contact.html.twig') 
                 ->context([
                     'emailFrom' => $data['email'],
                     'subject' => $data['subject'],
                     'message' => $data['message'],     
                 ]);
             $mailer->send($email);
             $this->addFlash('message', 'Votre message a bien été envoyé, 
             nous vous répondrons dans les plus brefs delais');
             return $this->redirectToRoute('app_home');
         }
        return $this->render('contact/contactUs.html.twig', [
            'form' => $form->createView(),
        ]); 
    }
}
