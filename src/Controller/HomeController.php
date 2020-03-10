<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Serie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {

        $categorie = new Categorie();

        $categorieRepository = $this->getDoctrine()->getRepository(Categorie::class);
        $allCategories = $categorieRepository->findAll();
        dump($allCategories);

        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        $formCategorie->handleRequest($request);

        if($formCategorie->isSubmitted() && $formCategorie->isValid())
        {

            $categorie = $formCategorie->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('home');

        }

        $seriesRepository = $this->getDoctrine()->getRepository(Serie::class)->findAll();



        return $this->render('home/index.html.twig', [
            'categorieForm' => $formCategorie->createView(),
            'categories' => $allCategories,
            'series' => $seriesRepository
        ]);

   
    }


}
