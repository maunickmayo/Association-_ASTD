<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entitymanager): Response
    {   
        $articles = $entitymanager->getRepository(Article::class)->findBy(['deletedAt' => null]);
    
        return $this->render('default/home.html.twig', [
            'articles' => $articles
        ]);
    }


    
    /**
     * @Route("/historique", name="historique", methods={"GET"})
     */
    public function notreHistorique(): Response
    {   
        return $this->render('pages/Historique.html.twig');
    }

    /**
     * @Route("/faire-un-don", name="faire-un-don", methods={"GET"})
     */
    public function faireUnDon(): Response
    {  
        return $this->render('pages/FaireUnDon.html.twig');
    }

    /**
     * @Route("/les-objectifs", name="objectifs", methods={"GET"})
     */
    public function lesObjectifs(): Response
    {   
     
        return $this->render('pages/Objectifs.html.twig');
    }

    /**
     * @Route("/questions-reponses", name="questions-reponses", methods={"GET"})
     */

    public function questionsReponses(): Response
    {   
     
        return $this->render('pages/QuestionsReponses.html.twig');
    }

    /**
     * @Route("/nos-actions", name="nos_actions", methods={"GET"})
     */
    public function nosActions(): Response
    {   
     
        return $this->render('pages/NosActions.html.twig');
    }

    /**
     * @Route("/nos-meetings", name="nos_meetings", methods={"GET"})
     */
    public function nosMeetings(): Response
    {   
     
        return $this->render('pages/NosMeetings.html.twig');
    }

    /**
     * @Route("/mentions-legales-politique-de-confidentialite", name="mentions_legales", methods={"GET"})
     */
    public function mentionsLegales(): Response
    {   
     
        return $this->render('pages/Mentionslegales.html.twig');
    }
    /**
     * @Route("/politique-de-confidentialite", name="politique_de_confidentialite", methods={"GET"})
     */
    public function politiqueDeconfidentialite(): Response
    {   
     
        return $this->render('pages/PolitiqueDeConfidentialite.html.twig');
    }
    
    /**
     * @Route("/agir-pour-sauver-les-enfants", name="agir_pour_sauver_les_enfants", methods={"GET"})
     */
    public function agirPourSauverLesEnfants(): Response
    {   
     
        return $this->render('pages/agirPourSauver.html.twig');
    }
    
    /**
     * @Route("/le-tiers-monde", name="le_tiers_monde", methods={"GET"})
     */
    public function leTiersMonde(): Response
    {   
     
        return $this->render('pages/LeTiersMonde.html.twig');
    }
    /**
     * @Route("/insalubrite", name="insalubrite", methods={"GET"})
     */
    public function insalubrite(): Response
    {   
     
        return $this->render('pages/insalubrite.html.twig');
    }
    
}



