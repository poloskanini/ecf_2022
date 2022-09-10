<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\Permissions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //?  ADMIN (ADMIN)
        $adminUser = new User();

        $adminUser
        ->setName('Nicolas Barthès ADMIN')
        ->setEmail('admin@admin.fr')
        ->setRoles(['ROLE_ADMIN'])
        ->setPassword($this->passwordHasher->hashPassword($adminUser, ('admin')));

        //?  USER 1 (PARTENAIRE)
        $orangeUser1 = new User();
        $orangePartner1 = new Partner();
        $orangePartnerPermissions = new Permissions();

        //?  USER 2 (STRUCTURE)
        $orangeUser2 = new User();
        $orangeStructure2 = new Structure();
        $orangeStructurePermissions = new Permissions();

        //?  USER 4 (STRUCTURE INACTIVE)
        $orangeUser4 = new User();
        $orangeStructure4 = new Structure();
        $orangeStructure4Permissions = new Permissions();

        //?  USER 5 (PARTENAIRE)
        $orangeUser5 = new User();
        $orangePartner5 = new Partner();
        $orangePartner5Permissions = new Permissions();
 
        // USER 1
        $orangeUser1
            ->setName('Directeur Orange Bleue DUNKERQUE')
            ->setEmail('dunkerque@partenaire.fr')
            ->setRoles(['ROLE_PARTENAIRE'])
            ->setPassword($this->passwordHasher->hashPassword($orangeUser1, ('dunkerque')))
        ;
        // PARTNER 1 (rattaché à User 1)
        $orangePartner1
            ->setName('L\'orange Bleue Dunkerque')
            ->setUser($orangeUser1)
            ->addStructure($orangeStructure2)
            ->addPermission($orangePartnerPermissions)
        ;

         // USER 5
        $orangeUser5
            ->setName('Directeur Pomme Jaune CALAIS')
            ->setEmail('calais@partenaire.fr')
            ->setRoles(['ROLE_PARTENAIRE'])
            ->setPassword($this->passwordHasher->hashPassword($orangeUser1, ('calais')))
        ;
        // PARTNER 5 (rattaché à User 5)
        $orangePartner5
            ->setName('La Pomme Jaune Calais')
            ->setUser($orangeUser5)
            ->addStructure($orangeStructure4)
            ->addPermission($orangePartner5Permissions)
        ;

        // USER 2
        $orangeUser2
            ->setName('Club Rue du Sable - Dunkerque')
            ->setEmail('ruedusable@structure.fr')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setPassword($this->passwordHasher->hashPassword($orangeUser2, ('sable')))
        ;
        // STRUCTURE 2 (rattaché à User 2)
        $orangeStructure2
            ->setUser($orangeUser2)
            ->setPartner($orangePartner1)
            ->setPostalAdress('3 rue du sable, Dunkerque (Structure 1)')
            ->addPermission($orangeStructurePermissions)
        ;

        // USER 4 INACTIF
        $orangeUser4
            ->setName('Club Inactif - Calais')
            ->setEmail('inactif@inactif.fr')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setIsActive(false)
            ->setPassword($this->passwordHasher->hashPassword($orangeUser1, ('inactif')))
        ;
        // STRUCTURE 4 INACTIVE (rattaché à User 3)
        $orangeStructure4
            ->setUser($orangeUser4)
            ->setPartner($orangePartner5)
            ->setPostalAdress('3 rue inactive')
            ->addPermission($orangeStructure4Permissions)
        ;

        // PERMISSIONS PARTNER 1
        $orangePartnerPermissions
            ->setIsPlanning('1')
            ->setIsNewsletter('1')
            ->setIsBoissons('1')
            ->setIsSms('0')
            ->setIsConcours('0')
        ;

        // PERMISSIONS PARTNER 5
        $orangePartner5Permissions
            ->setIsPlanning('0')
            ->setIsNewsletter('0')
            ->setIsBoissons('0')
            ->setIsSms('1')
            ->setIsConcours('1')
        ;

        // PERMISSIONS STRUCTURE 2
        $orangeStructurePermissions
            ->setIsPlanning($orangePartnerPermissions->isIsPlanning())
            ->setIsNewsletter($orangePartnerPermissions->isIsNewsletter())
            ->setIsBoissons($orangePartnerPermissions->isIsBoissons())
            ->setIsSms($orangePartnerPermissions->isIsSms())
            ->setIsConcours($orangePartnerPermissions->isIsConcours())
        ;


        // PERMISSIONS STRUCTURE 4
        $orangeStructure4Permissions
            ->setIsPlanning($orangePartner5Permissions->isIsPlanning())
            ->setIsNewsletter($orangePartner5Permissions->isIsNewsletter())
            ->setIsBoissons($orangePartner5Permissions->isIsBoissons())
            ->setIsSms($orangePartner5Permissions->isIsSms())
            ->setIsConcours($orangePartner5Permissions->isIsConcours())
        ;

        // PERMISSIONS PARTENAIRE 5
        $orangePartner5Permissions
            ->setIsPlanning($orangePartner5Permissions->isIsPlanning())
            ->setIsNewsletter($orangePartner5Permissions->isIsNewsletter())
            ->setIsBoissons($orangePartner5Permissions->isIsBoissons())
            ->setIsSms($orangePartner5Permissions->isIsSms())
            ->setIsConcours($orangePartner5Permissions->isIsConcours())
        ;


        // Commits
        $manager->persist($adminUser);
        $manager->persist($orangeUser1);
        $manager->persist($orangeUser2);
        $manager->persist($orangeUser4);
        $manager->persist($orangeUser5);
        $manager->persist($orangePartner1);
        $manager->persist($orangePartner5);
        $manager->persist($orangeStructure2);
        $manager->persist($orangeStructure4);
        $manager->persist($orangePartnerPermissions);
        $manager->persist($orangeStructure4Permissions);
        $manager->persist($orangePartner5Permissions);

        // Push
        $manager->flush();
    }
}