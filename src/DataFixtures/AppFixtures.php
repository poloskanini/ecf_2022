<?php

namespace App\DataFixtures;

use App\Entity\Partenaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $partenaire = new Partenaire();
        $partenaire
            ->setName('Partenaire');


        $manager->flush();
    }
}
