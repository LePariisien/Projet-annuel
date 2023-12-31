<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminPage(): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();
        $totalUsers = $userRepository->count([]);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'totalUsers' => $totalUsers,
        ]);
    }

    #[Route('/admin/files', name: 'admin_files')]
    public function listAllFiles(EntityManagerInterface $entityManager): Response
    {
        // Récupérez la liste de tous les utilisateurs et leurs fichiers
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/list_files.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/profile', name: 'admin_profile')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProfile(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur administrateur connecté
        $admin = $this->getUser();

        // Créer un formulaire pour mettre à jour le profil de l'administrateur
        $form = $formFactory->create(ProfileFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
        }

        return $this->render('admin/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
