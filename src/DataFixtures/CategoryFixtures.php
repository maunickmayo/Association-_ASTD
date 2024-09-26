<?php

namespace App\DataFixtures;

// les use servent à utiliser les classes
use App\Entity\Category;
use DateTime;                                        
use Doctrine\Bundle\FixturesBundle\Fixture; 
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    

        private SluggerInterface $slugger;  

        public function __construct(SluggerInterface $slugger)
        {
           $this->slugger= $slugger;
        }     

        public function load(ObjectManager $manager): void 
        {
           $categories = [
            'Politique',
            'société',
            'Sport',
            'Cinema',
            'Santé',
            'Sciences',
            'Musique',
            'Hi-Tech',
            'Eclogie'
        ];
         foreach($categories as $cat) {

             $category = new Category();

             $category ->setName($cat);
             $category ->setAlias($this->slugger->slug($cat)); 
       
             $category->setCreatedAt(new DateTime());
             $category->setUpdatedAt(new DateTime());
             $manager->persist($category);
           }

            # La méthode flush() n'est pas dans la boucle foreach() pour une raison :
            # => cette méthode "vide" l'objet $manager (c'est un container).
            $manager->flush();
        }
}
