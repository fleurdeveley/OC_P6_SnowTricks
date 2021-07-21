<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Display the home page
     * 
     * @Route("/", name="homepage")
     */
    public function homepage(TrickRepository $trickRepository)
    {
        $tricks = $trickRepository->findBy([], ['created_at' => 'DESC'], 10);

        return $this->render('home/home.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * To load the next 10 tricks
     * 
     * @Route("/loadmoretricks/{start}", name="loadmoretricks", requirements={"start": "\d+"})
     */
    public function loadMoreTricks(TrickRepository $trickRepository, $start = 10)
    {
        $tricks = $trickRepository->findBy([], ['created_at' => 'DESC'], 5, $start);

        return $this->render('home/tricks.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
