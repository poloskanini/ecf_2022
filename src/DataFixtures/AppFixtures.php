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

        //?  USER 3 (STRUCTURE)
        $orangeUser3 = new User();
        $orangeStructure3 = new Structure();
        $orangeStructure3Permissions = new Permissions();

        //?  USER 4 (STRUCTURE INACTIVE)
        $orangeUser4 = new User();
        $orangeStructure4 = new Structure();
        $orangeStructure4Permissions = new Permissions();
 
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

        // USER 2
        $orangeUser2
            ->setName('Gérant Structure Rue du Sable')
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

        // USER 3
        $orangeUser3
            ->setName('Gérant Structure Rue du Pélican')
            ->setEmail('ruedupelican@structure.fr')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setPassword($this->passwordHasher->hashPassword($orangeUser1, ('pelican')))
        ;
        // STRUCTURE 3 (rattaché à User 3)
        $orangeStructure3
            ->setUser($orangeUser3)
            ->setPartner($orangePartner1)
            ->setPostalAdress('12 rue du pelican, Dunkerque (Structure 2)')
            ->addPermission($orangeStructure3Permissions)
        ;

        // USER 4 INACTIF
        $orangeUser4
            ->setName('Inactif')
            ->setEmail('inactif@inactif.fr')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setIsActive(false)
            ->setPassword($this->passwordHasher->hashPassword($orangeUser1, ('inactif')))
        ;
        // STRUCTURE 4 INACTIVE (rattaché à User 3)
        $orangeStructure4
            ->setUser($orangeUser4)
            ->setPartner($orangePartner1)
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

        // PERMISSIONS STRUCTURE 2
        $orangeStructurePermissions
            ->setIsPlanning($orangePartnerPermissions->isIsPlanning())
            ->setIsNewsletter($orangePartnerPermissions->isIsNewsletter())
            ->setIsBoissons($orangePartnerPermissions->isIsBoissons())
            ->setIsSms($orangePartnerPermissions->isIsSms())
            ->setIsConcours($orangePartnerPermissions->isIsConcours())
        ;

        // PERMISSIONS STRUCTURE 3
        $orangeStructure3Permissions
            ->setIsPlanning($orangePartnerPermissions->isIsPlanning())
            ->setIsNewsletter($orangePartnerPermissions->isIsNewsletter())
            ->setIsBoissons($orangePartnerPermissions->isIsBoissons())
            ->setIsSms($orangePartnerPermissions->isIsSms())
            ->setIsConcours($orangePartnerPermissions->isIsConcours())
        ;

        // PERMISSIONS STRUCTURE 4
        $orangeStructure4Permissions
            ->setIsPlanning($orangePartnerPermissions->isIsPlanning())
            ->setIsNewsletter($orangePartnerPermissions->isIsNewsletter())
            ->setIsBoissons($orangePartnerPermissions->isIsBoissons())
            ->setIsSms($orangePartnerPermissions->isIsSms())
            ->setIsConcours($orangePartnerPermissions->isIsConcours())
        ;

        // Commits
        $manager->persist($adminUser);
        $manager->persist($orangeUser1);
        $manager->persist($orangeUser2);
        $manager->persist($orangeUser3);
        $manager->persist($orangeUser4);
        $manager->persist($orangePartner1);
        $manager->persist($orangeStructure2);
        $manager->persist($orangeStructure3);
        $manager->persist($orangeStructure4);
        $manager->persist($orangePartnerPermissions);
        $manager->persist($orangeStructure3Permissions);
        $manager->persist($orangeStructure4Permissions);


        // Push
        $manager->flush();
    }
}
