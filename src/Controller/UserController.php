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
        // on veut afficher ici les articles publiés , NB: l'admin est aussi un utilisateur donc il a aussi un profil
        return $this->render('user/show_profile.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
    * @Route("/modifier-mon-profil-{id}", name="edit_profile", methods={"GET|POST"})
    */
    public function editProfile(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        // $user = $this->getUser();  pour recup les infos de user
        $form = $this->createForm(EditProfileFormType::class, $user)
        ->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     // si le formulaire a été envoyé et valide, isValid (booléen) va aller voir s'il y'a des erreurs.
            $user->setUpdatedAt(new DateTime()); // pr que la date de modification soit differente de celle de creation.

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
        //NB: change password peut être mis ici ou a SecurityController (puisqu'il s'git ici d 'une action de sécurité).
        // rappel un DELIMITER ($) est un opération de fin d'instruction ( exp le ;)

        $form = $this->createForm(ChangePasswordFormType::class)
              ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user  */
            // qui sert a typer pour l'auto-completion , pr que l ide sache que $user c est pour UserEntity , pour que lorsqu'on fait une fêlche pour acceder aux methodes il nous liste tts les methodes qu'il y ' ds USER.
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);

            $user->setUpdatedAt(new Datetime());
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainPassword')->getData()));

            // get('plainPassword') inout en entier mais quand on met get('plainPassword')->getData() on veut les données qui st à l'interieur. plainPassword =>  veut dire mdp en clair.

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
                //-------------// et  tte cette partie c est pour creer le nom du fichier------------------
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);// il cree un slug associé à l'original name, pour eviter les noms commens ds la BDD; le slug c est un service loader;
                //uniqid() : un id unique qui sera genere au moment ou l'on appelle la méthode.

                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                // il va aller copier le contenu temporaire du ficher qu'on va uploader pour aller le stocker qqlpart ds notre appli.
                try {
                    $photo->move(
                        $this->getParameter('uploads_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   
                }
                //et si tout se passe bien on lui passe le newfilename apres avoir pu l'uploader au niveau de notre appli.
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
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                //-------------// et  tte cette partie c est pour creer le nom du fichier------------------
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);// il cree un slug associé à l'original name, pour eviter les noms commens ds la BDD; le slug c est un service loader;
                //uniqid() : un id unique qui sera genere au moment ou l'on appelle la méthode.

                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                // il va aller copier le contenu temporaire du ficher qu'on va uploader pour aller le stocker qqlpart ds notre appli.
                try {
                    $photo->move(
                        $this->getParameter('uploads_dir'),
                        $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    // echo 'Impossible d\'enregistrer l\'image';
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                //et si tout se passe bien on lui passe le newfilename apres avoir pu l'uploader au niveau de notre appli.
                $user->setPhoto($newFilename);
            }

            //$user->setPassword($passwordHasher->hashPassword($user, $user->getPassword() ));
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
           // $this->addFlash('message', 'Votre profil a bien été supprimé.');   
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