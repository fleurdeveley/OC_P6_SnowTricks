<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function create(Request $request, SluggerInterface $slugger,
    EntityManagerInterface $em)
    {
        $form = $this->createForm(TrickType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $trick =$form->getData();
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
     * @Route("/trick/edit/{slug}", name="trick_edit", methods={"GET","POST"})
     */
    public function edit()
    {

    }
}
