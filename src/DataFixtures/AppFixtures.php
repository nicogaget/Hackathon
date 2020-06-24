<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\

class AppFixtures extends Fixture
{
    const NB_PRACT = 1;
    const NB_CLIENT = 10;
    public function load(ObjectManager $manager)
    {
        $typeDoctor = new Type
        
        // $product = new Product();
        // $manager->persist($product);
       for($i = 0 ; $i < $this::NB_PRACT ; $i++) {
           $aPract = new User
       }
        
        $manager->flush();
    }
}
