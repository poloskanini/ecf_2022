<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Partner;
use App\Entity\Structure;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

         //NEW USER
         $adminUser = new User();
         $orangeUser1 = new User();
         $orangeUser2 = new User();
         $orangePartner1 = new Partner();
         $orangeStructure1 = new Structure();

         //ADMIN
         $adminUser
            ->setName('Nicolas Barthès')
            ->setEmail('admin@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword($adminUser, ('admin')));
 
         // USER 1
         $orangeUser1
             ->setName('Directeur Orange Bleue DUNKERQUE')
             ->setEmail('orangebleuedunkerque@partenaire.fr	')
             ->setRoles(['ROLE_PARTENAIRE'])
             ->setPassword($this->passwordHasher->hashPassword($orangeUser1, ('dunkerque')));
        
         //USER 2
        $orangeUser2
            ->setName('Club Structure rue du sable DUNKERQUE')
            ->setEmail('ruedusable@structure.fr	')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setPassword($this->passwordHasher->hashPassword($orangeUser2, ('sable')));

        // $orangeUser3
        //     ->setName('Club Structure rue Montmartre DUNKERQUE')
        //     ->setEmail('ruemontmartre@orangebleue.fr')
        //     ->setRoles(['ROLE_STRUCTURE'])
        //     ->setPassword($this->passwordHasher->hashPassword($orangeUser2, ('montmartre')));
        
        //  $orangeUser4
        //     ->setName('Directeur Orange Bleue CALAIS')
        //     ->setEmail('orangebleuecalais@direction.fr')
        //     ->setRoles(['ROLE_PARTENAIRE'])
        //     ->setPassword($this->passwordHasher->hashPassword($orangeUser4, ('calais')));
        //     ;

        // $orangeUser5
        // ->setName('Club Structure rue Napoléon CALAIS')
        // ->setEmail('ruenapoleon@orangebleue.fr')
        // ->setRoles(['ROLE_STRUCTURE'])
        // ->setPassword($this->passwordHasher->hashPassword($orangeUser4, ('napoleon')));
        // ;

          // PARTNER 1 (rattaché à User 1)
         $orangePartner1
             ->setName('L\'orange Bleue Dunkerque (Partner 1)')
             ->setUser($orangeUser1)
             ->setIsPlanning(1)
             ->setIsNewsletter(0)
             ->setIsBoissons(1)
             ->setIsSms(0)
             ->setIsConcours(1)
             ->addStructure($orangeStructure1);

         // STRUCTURE 1 (rattaché à User 2)
        $orangeStructure1
            ->setUser($orangeUser2)
            ->setPartner($orangePartner1)
            ->setPostalAdress('3 rue tartuffe, Dunkerque (Structure 1)')
            ->setIsPlanning($orangePartner1->isIsPlanning())
            ->setIsNewsletter($orangePartner1->isIsNewsletter())
            ->setIsBoissons($orangePartner1->isIsBoissons())
            ->setIsSms($orangePartner1->isIsSms())
            ->setIsConcours($orangePartner1->isIsConcours())
            ;


        // Commits
         $manager->persist($adminUser);
         $manager->persist($orangeUser1);
         $manager->persist($orangeUser2);
         $manager->persist($orangePartner1);
         $manager->persist($orangeStructure1);

        // Push
        $manager->flush();
    }
}
