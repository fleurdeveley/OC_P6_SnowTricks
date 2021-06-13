<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/tricks/details/{slug}", name="trick")
     */
    public function trick($slug, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$trick) {
            throw $this->createNotFoundException("La figure demandée n'existe pas.");
        }

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/admin/trick/create", name="trick_create")
     */
    public function create(Request $request, SluggerInterface $slugger,EntityManagerInterface $em) 
    {
        $trick = new Trick;

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreatedAt(new \DateTime());
            $trick->setUpdatedAt($trick->getCreatedAt());
            $trick->setSlug(strtolower($slugger->slug($trick->getName())));

            $em->persist($trick);
            $em->flush();
        }

        $formView = $form->createView();

        return $this->render('trick/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/trick/delete/{slug}", name="trick_delete")
     */
    public function delete($slug, TrickRepository $trick): Response
    {
        $trick = $trick->findOneBySlug($slug);

        $fileSystem = new Filesystem();

        foreach ($trick->getPictures() as $picture) {
            $fileSystem->remove($picture->getPath() . '/' . $picture->getName());
        }

        $em = $this->getDoctrine->getManager();
        $em->remove($trick);
        $em->flush();

        $this->addflash(
            'success',
            "La figure <strong>{{ $trick->getName() }}</strong> a bien été supprimée."
        );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/trick/{slug}/edit", name="trick_edit")
     */
    public function edit($slug, TrickRepository $trickRepository, Request $request, 
    EntityManagerInterface $em, ValidatorInterface $validator) 
    {
        $trick = $trickRepository->findOneBySlug($slug);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('trick', [
                'slug' => $trick->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'formView' => $formView
        ]);
    }
}
