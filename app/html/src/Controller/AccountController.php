<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
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

            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute('security_login');
        }

        $formView = $form->createView();

        return $this->render('account/register.html.twig', [
            'formView' => $formView
        ]);
    }
}
