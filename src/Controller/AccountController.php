<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * @Route("/logout", name="account_logout")
     */
    public function logout(): Response
    {
        return $this->render('account/login.html.twig');
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * @Route("/register", name="account_register")
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'sucess',
                'Votre compte a bien été créé ! Vous pouvez dés à present vous connecter !'
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView(),
            'exist' => false
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification du profile
     * @IsGranted ("ROLE_USER")
     * @Route("/account/profile", name="account_profile")
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'sucess',
                'Les données du profile ont été enregistrées avec succès !'
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
            'exist' => false
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * @IsGranted ("ROLE_USER")
     * @Route("/account/password-update", name="account_password")
     */
    public function updatePassword(UserPasswordEncoderInterface $encoder, Request $request, EntityManagerInterface $manager): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        //dd(password_verify($passwordUpdate->getOldPassword(), $user->getHash()));
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la concordances des mot de passes
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                //Gérer l'erreur
                $form->get('oldPassword')->addError(New FormError("Le mot de passe que vous avez tapé ne correspond pas à votre mot de passe actuel !"));

            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'sucess',
                    'Votre mot de passe a bien été modifié !'
                );
                return $this->redirectToRoute('home');
            }

        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'exist' => false
        ]);
    }

    /**
     * Permet d'afficher le profile de l'utilisateur connecté
     * @IsGranted ("ROLE_USER")
     * @Route("/account", name="account_index")
     */
    public function myAccount(): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher le profile de l'utilisateur connecté
     * @IsGranted ("ROLE_USER")
     * @Route("/account/bookings", name="account_bookings")
     */
    public function booking(): Response
    {
        return $this->render('account/bookings.html.twig', [
            'user' => $this->getUser()
        ]);
    }


}
