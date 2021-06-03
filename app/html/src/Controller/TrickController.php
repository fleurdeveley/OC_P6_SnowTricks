<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/trick/delete/{slug}", name="trick_delete")
     */
    public function delete($slug)
    {
        // $trick = $repo->findOneBySlug($slug);

        // $fileSystem = new Filesystem();

        // foreach ($trick->getPictures() as $picture) {
        //     $fileSystem->remove($picture->getPath() . '/' . $picture->getName());
        //     $fileSystem->remove($picture->getPath() . '/cropped/' . $picture->getName());
        //     $fileSystem->remove($picture->getPath() . '/thumbnail/' . $picture->getName());
        // }

        // $manager->remove($trick);
        // $manager->flush();

        // $this->addflash(
        //     'success',
        //     "Le trick <strong>{{ $trick->getName() }}</strong> a été supprimé avec succès !"
        // );

        return $this->redirectToRoute('homepage');
    }
}
