<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="account_register")
     */
    public function register(
        Request $request, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $hasher)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setAvatar('img/defaultAvatar.png');
            $user->setPassword($hasher->hashPassword($user, 
                   $request->request->get('register')['password']));
            $user->setRoles(['ROLES_USER']);
            $user->setActivated(false);
            $user->setToken(md5(random_bytes(10)));

            $em->persist($user);
            $em->flush();

            $email = new TemplatedEmail();
            $email->to(new Address($user->getEmail(), $user->getFullName()))
                ->from("noreply@snowtricks.com")
                ->subject("Validation de votre compte SnowTricks.")
                ->htmlTemplate('emails/validation.html.twig')
                ->context([
                    'user' => $user
                ]);
            
            $this->mailer->send($email);

            $this->addFlash(
                'success',
                "Compte crée avec succès ! Veuillez valider votre compte via le mail qui vous 
                a été envoyé pour pouvoir vous connecter !"
            );
            
            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('account/register.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * Email validation after registration
     *
     * @Route("/email-validation/{email}/{token}", name="email_validation")
     */
    public function emailValidation(
        UserRepository $userRepository, 
        $email, 
        $token, 
        EntityManagerInterface $em)
    {
        $user = $userRepository->findOneByEmail($email);

        if($token != null && $token === $user->getToken()) {
            $user->setActivated(true);
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "Votre compte a été activé avec succès ! Vous pouvez désormais vous connecter !"
            );
        } else {
            $this->addFlash(
                'danger',
                "La validation de votre compte a échoué. Le lien de validation a expiré !"
            );   
        }

        return $this->redirectToRoute('security_login'); 
    }

    /**
     * Password forgotten by user : reset
     * 
     * @Route("/account/forgot_password", name="account_forgot_password")
     */
    public function forgot_password(
        Request $request, 
        UserRepository $userRepository)
    {
        $form = $this->createForm(PasswordForgotType::class);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
            $user = $userRepository->findOneByEmail($email['email']);

            if($user !== null) {
                $email = new TemplatedEmail();
                $email->to(new Address($user->getEmail(), $user->getFullName()))
                    ->from("noreply@snowtricks.com")
                    ->subject("Réinitialisation du mot de passe de votre compte SnowTricks.")
                    ->htmlTemplate('emails/reset.html.twig')
                    ->context([
                        'user' => $user
                    ]);
                
                $this->mailer->send($email);

                $this->addFlash(
                    'success',
                    "Un email de réinitilisation de mot de passe vous a été envoyé !"
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Cet utilisateur n'existe pas !"
                );
            }
        }

        $formView = $form->createView();

        return $this->render('account/forgot_password.html.twig', [
            'formView' => $formView,
        ]);
    }

    /**
     * Password reset if the token is correct
     * 
     * @Route("/account/reset_password/{email}/{token}", name="account_reset_password")
     */
    public function reset_password(
        Request $request, 
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em, 
        $email, 
        $token)
    {
        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($email);

            if($token != null && $token === $user->getToken()) {
                $password = $request->request->get('password_reset')['password']['first'];
                $user->setPassword($hasher->hashPassword($user, $password));

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe est modifié avec succés !"
                );

                return $this->redirectToRoute('security_login');

            } else {
                $this->addFlash(
                    'danger',
                    "La reinitialisation du mot de passe a échoué. Le lien de reinitialisation 
                    a expiré !"
                );   
            }
        }

        $formView = $form->createView();

        return $this->render('account/reset_password.html.twig', [
            'formView' => $formView,
        ]);
    }
}
