<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Article;
use App\Form\PhotoFormType;
use App\Form\EditProfileFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



/**
* @Route("/espace_membre")
*/
class UserController extends AbstractController
{

    /**
    * @Route("/profil-{id}", name="app_profile")
    */
    public function showProfile(EntityManagerInterface $entityManager): Response
    {  
        $articles = $entityManager->getRepository(Article::class)->findBy(['author' => $this->getUser()]);
        return $this->render('user/show_profile.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
    * @Route("/modifier-mon-profil-{id}", name="edit_profile", methods={"GET|POST"})
    */
    public function editProfile(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditProfileFormType::class, $user)
        ->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', "Profil mis à jour avec succès !");
            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId()
            ]);
        } # end if ($form)

        return $this->render('user/edit_profile.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/modifier-mot-de-passe", name="edit_password", methods={"GET|POST"})
    */
    public function editPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $form = $this->createForm(ChangePasswordFormType::class)
              ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user  */
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);

            $user->setUpdatedAt(new Datetime());
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainPassword')->getData()));

            $entityManager->persist($user);
            $entityManager->flush();

            $this ->addFlash('message', "Mot de passe mis à jour avec succès");
            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/edit_password.html.twig', [
          'editPassform' => $form->createView()
        ]);
    }

     /**
     * @Route("/ajouter-photo-profil-{id}", name="add_photo_profil", methods={"GET|POST"})
     */
     public function addPhotoProfil(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
     {
       
        $form = $this->createForm(PhotoFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
               
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move(
                        $this->getParameter('uploads_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   
                }
                $user->setPhoto($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', "Photo ajoutée avec succès !");
            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/photo_profil.html.twig', [
          'user' => $user,
          'form' => $form->createView()

          
        ]);
    }

     /**
     * @Route("/modifier-photo-profil-{id}", name="update_photo_profil", methods={"GET|POST"})
     */
     public function updatePhotoProfil(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
     {
       
         # gestion de la photo
         $originalPhoto = $user->getPhoto();

        $form = $this->createForm(PhotoFormType::class, $user
        )->handleRequest($request);
        
        
        if($form->isSubmitted() && $form->isValid()) {

            $user->setUpdatedAt(new DateTime());

              /** @var UploadedFile $photo */
              $photo = $form->get('photo')->getData();
            
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
              
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                
                try {
                    $photo->move(
                        $this->getParameter('uploads_dir'),
                        $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setPhoto($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', "Photo modifiée avec succès !");
            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/photo_profil.html.twig', [
          'user' => $user,
          'form' => $form->createView(),
          'photo' => $originalPhoto

          
        ]);
    }

   
      

    /**
    * @Route("/supprimer-mon-profil-{id}", name="hard_delete_profil", methods={"GET"})
    */
    public function DeleteProfil(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {   

      $session = new Session();  
      
      $currentsession = $user->getId();

          if ($currentsession){
           
            $session->invalidate();
            $entityManager->remove($user); 
            $entityManager->flush();  
           }
           $this->addFlash('message', 'Votre profil a bien été supprimé.'); 
           return $this->redirectToRoute('app_register'); 
         
     } # end fucntion softDeleteMembre



     /**
     * @Route("/profil-photo-{id}", name="show_profile_photo", methods={"GET"})
     */
    public function showPhotoProfile(User $user, EntityManagerInterface $entityManager): Response
    {  
        $photos = $entityManager->getRepository(User::class)->findBy(['photo' => null]);
        return $this->render('user/show_photoProfile.html.twig', [
            'id' => $user->getId(),
            'photo' => $photos
        ]);
    }
    
  

     
}
