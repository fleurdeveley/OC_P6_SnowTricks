<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        if(!$trick) {
            throw $this->createNotFoundException("La figure demandée n'existe pas.");
        }

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }
}
