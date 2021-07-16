<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    /**
     * @Route("/tricks/details/{slug}", name="trick")
     */
    public function trick($slug, TrickRepository $trickRepository, 
    EntityManagerInterface $em, Request $request)
    {
        $trick = $trickRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$trick) {
            throw $this->createNotFoundException("La figure demandée n'existe pas.");
        }

        $comment = new Comment;

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt($comment->getCreatedAt());
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre commentaire a bien été enregistré !'
            );
        }

        $formView = $form->createView();

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'formView' => $formView,
            'start' => 0
        ]);
    }

    /**
     * @Route("/trick/create", name="trick_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em) 
    {
        $trick = new Trick;

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($trick->getPictures() as $picture) {
                $file = $picture->getFile();
                $file->move('../public/img', $file->getClientOriginalName());
                $picture->setSrc('/img/' . $file->getClientOriginalName());
                $picture->setTrick($trick);

                $em->persist($picture);
            }

            foreach($trick->getvideos() as $video) {
                $video->setTrick($trick);
                $em->persist($video);
            }

            $trick->setCreatedAt(new \DateTime());
            $trick->setUpdatedAt($trick->getCreatedAt());
            $trick->setSlug(strtolower($slugger->slug($trick->getName())));
            $trick->setUser($this->getUser());

            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre figure a bien été enregistrée !'
            );

            return $this->redirectToRoute('homepage');            
        }

        $formView = $form->createView();

        return $this->render('trick/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/trick/delete/{slug}", name="trick_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete($slug, TrickRepository $trick, EntityManagerInterface $em): Response
    {
        $trick = $trick->findOneBySlug($slug);

        $fileSystem = new Filesystem();

        foreach ($trick->getPictures() as $picture) {
            $fileSystem->remove($picture->getSrc() . '/' . $picture->getName());
        }

        $em->remove($trick);
        $em->flush();

        $this->addflash(
            'success',
            "La figure a bien été supprimée."
        );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/trick/{slug}/edit", name="trick_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit($slug, TrickRepository $trickRepository, Request $request, 
    EntityManagerInterface $em) 
    {
        $trick = $trickRepository->findOneBySlug($slug);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($trick->getPictures() as $picture) {
                $file = $picture->getFile();

                if($file !== null) {
                    $file->move('../public/img', $file->getClientOriginalName());
                    $picture->setSrc('/img/' . $file->getClientOriginalName());
                    $picture->setTrick($trick);
                }

                $em->persist($picture);
            }

            foreach($trick->getvideos() as $video) {
                $video->setTrick($trick);
                $em->persist($video);
            }

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

    /**
     * To load the next 5 comments
     * 
     * @Route("/loadmorecomments/{slug}/{start}", name="loadmorecomments", 
     * requirements={"start": "\d+"})
     */
    public function loadMoreComments(TrickRepository $trickRepository, $slug, $start = 5)
    {
        $trick = $trickRepository->findOneBySlug($slug);

        return $this->render('trick/comment.html.twig', [
            'trick' => $trick,
            'start' => $start
        ]);
    }
}
