<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Classe\Search;
use App\Form\UserType;
use App\Entity\Partner;
use App\Form\SearchType;
use App\Entity\Structure;
use App\Form\UserShowType;
use App\Entity\Permissions;
use App\Form\StructureType;
use App\Form\PermissionsType;
use App\Form\StructureFormType;
use App\Repository\UserRepository;
use App\Form\StructureFormShowType;
use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PermissionsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/structure')]
#[IsGranted('ROLE_ADMIN')]
class StructureController extends AbstractController
{
    public function __construct(
        public EntityManagerInterface $entityManager
        ) {}

    // INDEX FOR ALL STRUCTURES IN DB
    #[Route('/', name: 'app_structure_index', methods: ['GET'])]
    public function index(Request $request,PartnerRepository $partnerRepository, StructureRepository $structureRepository, PermissionsRepository $permissionsRepository, ManagerRegistry $doctrine): Response
    {
        $structures = $structureRepository->findAll();
        foreach ($structures as $structure) {
        // Catch le partner qui a l'id ciblée
        $structureUser = $structure->getUser(); // Catch l'utilisateur relié à ce partner
        }
        
        // Je récupère les permissions d'ORIGINE du Partner
        $partnerPermissions = $structure->getPartner()->getPermissions()->getValues();
        foreach ($partnerPermissions as $pp) {
            $permPartnerId = $pp->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }

        $partnerPermissions = $doctrine->getRepository(Permissions::class)->find($permPartnerId);
        // De cette façon, j'ai récupéré mon objet Entity\Permissions. Il s'agit du partner_permissions.

        // dump($partnerPermissions);
        // die;

        // Récupérer les permissions du partenaire
        // Ici, on a un Persistent Collection. Je le transforme en array pour pouvoir le parcourir.
        $permArray = ($structure->getPermissions()->getValues());
        foreach ($permArray as $p) {
            $permId = $p->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }

        $userPermissions = $doctrine->getRepository(Permissions::class)->find($permId);
        // De cette façon, j'ai récupéré mon objet Entity\Permissions. Il s'agit du structure_permissions.

        $items = ['user' => $structureUser, 'structure' => $structure, 'permissions' => $userPermissions]; // Tableau regroupant les 2 entités

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $structures = $this->entityManager->getRepository(Structure::class)->findWithSearch($search);
        }


        return $this->render('structure/index.html.twig', [
            'structures' => $structures,
            'partners' => $partnerRepository->findAll(),
            'permissions' => $permissionsRepository->findAll(),
            'partnerPermissions' => $partnerPermissions,
            'form' => $form->createView()
        ]);
    }

    // CREATE A NEW STRUCTURE
    #[Route('/new', name: 'app_structure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, StructureRepository $structureRepository, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): Response
    {
        $notification = null;

        $user = new User(); // J'instancie ma classe User()
        $structure = new Structure(); // J'instancie ma classe User()
        $permissions = new Permissions();
        
        $form = $this->createForm(StructureType::class); // Mon formulaire UserType

        $form->handleRequest($request); // Écoute la requête entrante

        if ($form->isSubmitted() && $form->isValid()) {

            // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
            $user = $form->getData();

            // Vérifier que mon partner n'est pas déjà présent en BDD
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                
                // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
                $password = $passwordHasher->hashPassword($user, $user->getPassword());
                // Je réinjecte $password qui est crypté dans l'objet User()
                $user->setPassword($password);

                // Je récupère les données non mappées de postalAdress et les injecte dans le setPostalAdress de ma structure.
                $structure->setPostalAdress($form->get('postalAdress')->getData());
                // Je définis que la nouvelle donnée aura par défaut le ['ROLE_STRUCTURE]
                $user->setRoles(['ROLE_STRUCTURE']);

                //Je définis que la structure de mon User est $structure
                $structure->setUser($user);
                $user->setStructure($structure);

                // Je définis que le partenaire de ma structure est la data que contient "id"
                $structure->setPartner($form->get('id')->getData());

                // dump($structure->getPartner()->getPermissions()->getValues());
                // die;
                // Récupérer les permissions du partenaire
                $permArray = ($structure->getPartner()->getPermissions()->getValues()); // Ici, on a un Persistent Collection. Je le transforme en array pour pouvoir le parcourir.
                foreach ($permArray as $p) {
                    $permId = $p->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
                }

                $userPermissions = $doctrine->getRepository(Permissions::class)->find($permId); // De cette façon, j'ai récupéré mon objet Entity\Permissions

                $permissions->setIsPlanning($userPermissions->isIsPlanning());
                $permissions->setIsNewsletter($userPermissions->isIsNewsletter());
                $permissions->setIsBoissons($userPermissions->isIsBoissons());
                $permissions->setIsSms($userPermissions->isIsSms());
                $permissions->setIsConcours($userPermissions->isIsConcours());

                $structure->addPermission($permissions);
                $permissions->addStructure($structure);

                $userRepository->add($user, true);
                $structureRepository->add($structure, true);

                $this->addFlash(
                    'success',
                    'La structure "' .$user->getName(). '" a été ajoutée avec succès. Elle appartient au partenaire "' .$structure->getPartner(). '".'
                );

                //**** ENVOI DU  MAIL DE CONFIRMATION de création de Structure ****\\\
                $resetPasswordUrl = $this->generateUrl('app_reset_password');

                $mail1 = new Mail();
                $mail2 = new Mail();

                // Envoi d'un mail au partenaire rattaché à la structure :
                $partnerSelectedEmail = $structure->getPartner()->getUser()->getEmail();
                $partnerSelectedName = $structure->getPartner()->getName();

                // Contenu
                $content = "Bonjour " .$partnerSelectedName. "<br/><br/> Félicitations ! Une nouvelle STRUCTURE appartenant à ".$user->getName()." et située à ".$structure->getPostalAdress()." a été ajoutée et liée à votre compte PARTENAIRE. <br/> Son email de connexion est " .$user->getEmail().". Elle dispose donc par défaut des mêmes permissions auxquelles votre contrat vous donne droit, et pourra choisir de les activer ou désactiver selon ses souhaits. <br><br/> <br/><br/><br/> A très bientôt chez STUDI FITNESS !";

                // Envoi
                $mail1->send($partnerSelectedEmail, $user->getName(), 'Une nouvelle structure pour votre franchise a été ajoutée !', $content);

                // Envoi d'un mail à la structure :
                // Contenu :
                $content = "Bonjour " .$user->getName(). "<br/><br/>Vous disposez désormais d'un compte STRUCTURE pour votre établissement à l'adresse : ".$structure->getPostalAdress(). ", et d'un accès en lecture seule au panel d'administration de STUDI FITNESS.<br/><br/> Vous pourrez y découvrir vos informations sur votre structure et le partenaire auquel vous êtes rattachée.<br/><br/> Votre email de connexion est " .$user->getEmail(). ", et votre mot de passe est " .$user->getPassword(). "<br><br/> Ce mot de passe est temporaire, vous pouvez le redéfinir en <a href='".$resetPasswordUrl."'> cliquant ici </a> .<br/><br/><br/> A très bientôt chez STUDI FITNESS !";

                // Envoi
                $mail2->send($user->getEmail(), $user->getName(), 'Vous avez un nouveau compte STRUCTURE !', $content);
                // ***************************************************************** \\\


                return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);

            } else {
                // Notification email si l'utilisateur est déja enregistré
                $notification = "L'email que vous avez renseigné existe déjà.";
            }
        }
        
        return $this->renderForm('structure/_new.html.twig', [
            'user' => $user,
            'structure' => $structure,
            'form' => $form,
            'notification' => $notification,
        ]);
    }

    // EDIT A STRUCTURE
    #[Route('/edit/{id}', name: 'app_structure_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, UserRepository $userRepository,  StructureRepository $structureRepository, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {
        $structure = $structureRepository->findOneBy(['id' => $id]); // Catch le partner qui a l'id ciblée
        $structureUser = $structure->getUser(); // Catch l'utilisateur relié à ce partner

        // Je récupère les permissions d'ORIGINE du Partner
        $partnerPermissions = $structure->getPartner()->getPermissions()->getValues();
        foreach ($partnerPermissions as $pp) {
            $permPartnerId = $pp->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }

        $partnerPermissions = $doctrine->getRepository(Permissions::class)->find($permPartnerId);
        // De cette façon, j'ai récupéré mon objet Entity\Permissions. Il s'agit du partner_permissions.

        // Récupérer les permissions du partenaire
        // Ici, on a un Persistent Collection. Je le transforme en array pour pouvoir le parcourir.
        $permArray = ($structure->getPermissions()->getValues());
        foreach ($permArray as $p) {
            $permId = $p->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }
       
        $userPermissions = $doctrine->getRepository(Permissions::class)->find($permId);
        // De cette façon, j'ai récupéré mon objet Entity\Permissions. Il s'agit du structure_permissions.  

        $items = ['user' => $structureUser, 'structure' => $structure, 'permissions' => $userPermissions]; // Tableau regroupant les 2 entités

        $form = $this->createFormBuilder($items) // Formulaire regroupant les 2 entités
            ->add('user', UserType::class, [
                'isEdit' => true,
            ])
            ->add('structure', StructureFormType::class)
            ->add('permissions', PermissionsType::class)
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
                // $password = $passwordHasher->hashPassword($structureUser, $structureUser->getPassword());
                // $structureUser->setPassword($password);
                
                // Cabler pour que les données du formulaire permissions aillent dans $structure->addPermissions()
                // $partner->setIsPlanning($userPermissions->isIsPlanning());
    
                // Je déclare que ma structure a de nouvelles permissions et que cet objet permissions a une nouvelle structure

                // $structure->addPermission($userPermissions);
                // $userPermissions->addStructure($structure);

                $em->persist($userPermissions);
                // $em->persist($partnerPermissions);
                $em->persist($structureUser);
                $em->persist($structure);

                
                $em->flush();

                // $userRepository->add($structureUser, true);
                // $structureRepository->add($structure, true);
    
                $this->addFlash(
                    'success',
                    // 'La structure "' .$structureUser->getName(). '" a été modifiée avec succès'
                    'La structure "' .$structureUser->getName(). '" appartenant au partenaire "' .$structure->getPartner(). '" a été modifiée avec succès.'
                );

                //**** ENVOI DU MAIL DE MODIFICATION de STRUCTURE ****\\\
                $mail = new Mail();
                $mail2 = new Mail();
                
                $content = "Bonjour " .$structureUser->getName(). "<br/><br/>";
                $content .= "Suite à votre demande, les informations de votre structure situé ".$structure->getPostalAdress(). " ont été mises à jour par un administrateur STUDI FITNESS. Vous les retrouverez ci-dessous :<br/><br/>";
                $content .= "<hr>";
                $content .= "<h3>Vos informations de connexion :";
                $content .= "<h5>Email de connexion : " .$structureUser->getEmail();
                $content .= "<h5>Nom de l'utilisateur : " .$structureUser->getName();
                $content .= "<h5>Nom de votre partenaire : " .$structure->getPartner()->getName();
                $content .= "<hr>";
                $content .= "<h3>Vos fonctionnalités activées :";

                // Envoi des permissions
                if ($userPermissions->isIsPlanning() == true) {
                    $content .=  "<h5>Planning : OK";
                }
                if ($userPermissions->isIsNewsletter() == true) {
                    $content .=  "<h5>Newsletter : OK";
                }
                if ($userPermissions->isIsBoissons() == true) {
                    $content .=  "<h5>Boissons : OK";
                }
                if ($userPermissions->isIsSms() == true) {
                    $content .=  "<h5>SMS : OK";
                }
                if ($userPermissions->isIsConcours() == true) {
                    $content .=  "<h5>Concours : OK";
                }

                $content .= "<hr>";
                $content .= "Pour toute autre besoin de modification, veuillez contacter <a href='#'> l'administrateur STUDI FITNESS </a>";
                
                $mail->send($structureUser->getEmail(), $structureUser->getName(), 'Mise à jour de vos informations et permissions STRUCTURE', $content);


                //**** ENVOI DU MAIL DE MODIFICATION AU PARTENAIRE DE LA STRUCTURE MODIFIÉE :
                $partnerSelectedEmail = $structure->getPartner()->getUser()->getEmail();
                $partnerSelectedName = $structure->getPartner()->getName();

                // Contenu
                $content = "Bonjour " .$partnerSelectedName. "<br/><br/>";
                $content .= "Votre STRUCTURE située au ".$structure->getPostalAdress(). " a effectuée des modifications de ses informations auprès de STUDI FITNESS. Vous les retrouverez ci-dessous :<br/><br/>";
                $content .= "<hr>";
                $content .= "<h3>Informations de votre structure :";
                $content .= "<h5>Nom de l'utilisateur : " .$structureUser->getName();
                $content .= "<h5>Adresse postale : " .$structure->getPostalAdress();
                $content .= "<hr>";
                $content .= "<h3>Ses fonctionnalités activées :";

                // Envoi des permissions
                if ($userPermissions->isIsPlanning() == true) {
                    $content .=  "<h5>Planning : OK";
                }
                if ($userPermissions->isIsNewsletter() == true) {
                    $content .=  "<h5>Newsletter : OK";
                }
                if ($userPermissions->isIsBoissons() == true) {
                    $content .=  "<h5>Boissons : OK";
                }
                if ($userPermissions->isIsSms() == true) {
                    $content .=  "<h5>SMS : OK";
                }
                if ($userPermissions->isIsConcours() == true) {
                    $content .=  "<h5>Concours : OK";
                }

                $content .= "<hr>";
                $content .= "Pour toute autre besoin de modification, veuillez contacter <a href='#'> l'administrateur STUDI FITNESS </a>";

                // Envoi
                $mail2->send($partnerSelectedEmail, $partnerSelectedName, 'Mise à jour des informations de l\'une de vos structures', $content);
                // ***************************************************************** \\\
    
                return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
            }

        return $this->renderForm('structure/_edit.html.twig', [
            'structure' => $structure,
            'form' => $form,
            'permissions' => $userPermissions,
            'partnerPermissions' => $partnerPermissions
        ]);

    }

    // SHOW A STRUCTURE
    #[Route('/show/{id}', name: 'app_structure_show', methods: ['GET'])]

    public function show(int $id, Request $request, StructureRepository $structureRepository, ManagerRegistry $doctrine,): Response
    {
        $structure = $structureRepository->findOneBy(['id' => $id]); // Catch le partner qui a l'id ciblée
        $structureUser = $structure->getUser(); // Catch l'utilisateur relié à ce partner

        // Je récupère les permissions d'ORIGINE du Partner
        $partnerPermissions = $structure->getPartner()->getPermissions()->getValues();
        foreach ($partnerPermissions as $pp) {
            $permPartnerId = $pp->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }

        $partnerPermissions = $doctrine->getRepository(Permissions::class)->find($permPartnerId);
        // De cette façon, j'ai récupéré mon objet Entity\Permissions. Il s'agit du partner_permissions.

        // dump($partnerPermissions);
        // die;

        // Récupérer les permissions du partenaire
        // Ici, on a un Persistent Collection. Je le transforme en array pour pouvoir le parcourir.
        $permArray = ($structure->getPermissions()->getValues());
        foreach ($permArray as $p) {
            $permId = $p->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }
       
        $userPermissions = $doctrine->getRepository(Permissions::class)->find($permId);
        // De cette façon, j'ai récupéré mon objet Entity\Permissions. Il s'agit du structure_permissions.
      

        $items = ['user' => $structureUser, 'structure' => $structure, 'permissions' => $userPermissions]; // Tableau regroupant les 2 entités

        $form = $this->createFormBuilder($items) // Formulaire regroupant les 2 entités
            ->add('user', UserShowType::class, [
                'isEdit' => true,
            ])
            ->add('structure', StructureFormShowType::class)
            ->add('permissions', PermissionsType::class)
            // ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            $form->handleRequest($request);
   

        return $this->renderForm('structure/_show.html.twig', [
            'structure' => $structure,
            'form' => $form,
            'permissions' => $userPermissions,
            'partnerPermissions' => $partnerPermissions
        ]);
    }

    // DELETE A STRUCTURE
    #[Route('/{id}/delete', name: 'app_structure_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Structure $structure) {
        
        if(!$structure) {
            $this->addFlash(
                'warning',
                'L\utilisateur n\'a pas été trouvé'
                );
                return $this->render('user/_delete.html.twig', [
                    'structure' => $structure,
                ]);
            }
            
            $manager->remove($structure); //REMOVE
            $manager->flush();
            
            $this->addFlash(
                'danger',
                'La structure localisée "' .$structure->getPostalAdress(). '" a été supprimée avec succès.'
        );

        return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}
