<?php
// src/Controller/RegistrationController.php

// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('mdp')->getData();
            $confirmPassword = $form->get('confirmMdp')->getData();

            // Vérifier si les mots de passe correspondent
            if ($plainPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            } else {
                // Vérifier si l'email existe déjà
                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
                if ($existingUser) {
                    $this->addFlash('error', 'Un utilisateur avec cet email existe déjà.');
                } else {
                    // Encoder le mot de passe
                    $user->setMdp(password_hash($plainPassword, PASSWORD_BCRYPT));

                    // Assigner le rôle USER par défaut
                    $user->setRoles(['ROLE_USER']);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_register_success'); // Utiliser le nom correct de la route
                }
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/success', name: 'app_register_success')]
    public function success(): Response
    {
        return $this->render('registration/success.html.twig');
    }
}
