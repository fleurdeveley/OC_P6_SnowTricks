<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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

        if($token != null && $token === $user->getToken())
        {
            $user->setActivated(true);
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "Votre compte a été activé avec succès ! Vous pouvez désormais vous connecter !"
            );
        }
        else
        {
            $this->addFlash(
                'danger',
                "La validation de votre compte a échoué. Le lien de validation a expiré !"
            );   
        }

        return $this->redirectToRoute('security_login'); 
    }
}
