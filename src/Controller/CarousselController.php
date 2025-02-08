<?php

namespace App\Controller;

use DateTime;
use App\Entity\Caroussel;
use App\Form\CarousselFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
* @Route("/admin")
*/
class CarousselController extends AbstractController
{
     /**
     * @Route("/caroussel", name="create_caroussel", methods= {"GET|POST"})
     */
     public function CreateCaroussel(Request $request, EntityManagerInterface $entitymanager, SluggerInterface $slugger): Response
     {

       try {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (AccessDeniedException $exception) {
        $this->addFlash('message', 'Cette partie du site est réservée aux admins');
        return $this->redirectToRoute('app_home');
        }  

         $caroussel = new Caroussel();
        
         $form = $this->createForm(CarousselFormType::class, $caroussel);
         $form->handleRequest($request); 

         if ($form->isSubmitted() && $form->isValid()) {

            $caroussel->setCreatedAt(new DateTime());
            $caroussel->setUpdatedAt(new DateTime());

         /** @var UploadedFile $photo */
         $photo = $form->get('photo')->getData();
       
               if ($photo) {
               
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                     $photo->move(
                        $this->getParameter('slider_dir'),
                         $newFilename
                    );
                } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                }

                $caroussel->setPhoto($newFilename);
                }
                $entitymanager->persist($caroussel);
                $entitymanager->flush();
                $this->addFlash('message', "Image ajoutée  avec succes !");
                return $this->redirectToRoute('caroussel_list');
        }
        // ... persist the $product variable or any other work
        
        return $this->render('admin/form/caroussel.html.twig', [
        'form' => $form->createView(),
        'caroussel' => $caroussel
        ]);

    } 
    /**
    * @Route("/espace-admin/carousel", name="caroussel_list", methods={"GET"})
    */
    public function showCarouselList(EntityManagerInterface $entitymanager): Response
    {  
     
       //------------------------------------------
       $caroussels = $entitymanager->getRepository(Caroussel::class)->findBy(['deletedAt' => null]);
     return $this->render('caroussel/caroussel_list.html.twig', [
       'caroussels' => $caroussels,
       
       
     ]);
     
   }#END FUNCTION Show caroussel    
      

    /**
    * @Route("/espace-admin/modifier-image-caroussel-{id}", name="update_image_caroussel", methods={"GET|POST"})
    */
    public function updateImageCaroussel(Caroussel $caroussel, Request $request, EntityManagerInterface $entitymanager, SluggerInterface $slugger): Response
    {  
       
        # 2 - Création du formulaire
        $form = $this->createForm(CarousselFormType::class, $caroussel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

          $caroussel->setUpdatedAt(new DateTime());

      
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

           
            if($photo) {

                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('slider_dir'), $newFilename);// uploads_dir'=> est le path de l'image.
                    $caroussel->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } # end if($photo)

        
            $entitymanager->persist($caroussel);
            $entitymanager->flush();

            $this->addFlash('message', "L'image a été modifiée avec succès !");
            return $this->redirectToRoute('caroussel_list', [
              'id' => $caroussel->getId()
            ]);
            } # end if ($form)

        return $this->render('admin/form/caroussel.html.twig', [
          'form' => $form->createView(),
          'caroussel' => $caroussel
          ]);
      } 
       


     /**
     * @Route("/espace-admin/archiver-une-image-{id}", name="soft_delete_caroussel_image", methods={"GET"})
     */
     public function softDeleteCarousselImage(Caroussel $caroussel, EntityManagerInterface $entitymanager): Response
     {
      $caroussel->setDeletedAt(new DateTime());

      $entitymanager->persist($caroussel);
      $entitymanager->flush();

        $this->addFlash('message', "L'image a bien été archivée");
        return $this->redirectToRoute('caroussel_list', [
          'id' => $caroussel->getId()
        ]);
     }# end function softDelete

    /**
    * @Route("/espace-admin/restaurer-une-image-{id}", name="restore_caroussel_image", methods={"GET"})
    */
    public function restoreCarousselImage(Caroussel $caroussel, EntityManagerInterface $entitymanager): RedirectResponse
    {
      $caroussel->setDeletedAt(null);

      $entitymanager->persist($caroussel);
      $entitymanager->flush();

        $this->addFlash('message', "L'image a bien été restaurée");
        return $this->redirectToRoute('caroussel_trash');
    }

    /**
    * @Route("/espace-admin/voir-les-images-archives", name="caroussel_trash", methods={"GET"})
    */
    public function showTrashCaroussel(EntityManagerInterface $entitymanager): Response
    { 
       $archivedCaroussels = $entitymanager->getRepository(Caroussel::class)->findByTrash();

        return $this->render("admin/trash/caroussel_trash.html.twig", [
            'archivedCaroussels' => $archivedCaroussels
        ]);
    }

    /**
    * @Route("/espace-admin/supprimer-caroussel-image-{id}", name="hard_delete_caroussel_image", methods={"GET"})
    */
    public function hardDeleteCarousselImage(Caroussel $caroussel, EntityManagerInterface $entitymanager): RedirectResponse
    {
        $photo = $caroussel->getPhoto();

        if($photo) {
          unlink($this->getParameter('slider_dir'). '/' . $photo); 
        }

        $entitymanager->remove($caroussel);
        $entitymanager->flush();

        $this->addFlash('message', "La photo bien été supprimé de la base de données");
        return $this->redirectToRoute('caroussel_trash');
    }
 } 
