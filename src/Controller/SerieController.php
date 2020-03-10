<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Serie;
use App\Form\SerieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class SerieController extends AbstractController
{
    /**
     * @Route("/serie", name="serie")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)  
    {

        $serie = new Serie();

        $serieRepository = $this->getDoctrine()->getRepository(Serie::class);
        $series = $serieRepository->findAll();


        $categorieRepository = $this->getDoctrine()->getRepository(Categorie::class);
        $allCategories = $categorieRepository->findAll();
        


        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);


        if($serieForm->isSubmitted() && $serieForm->isValid()){

            $serie = $serieForm->getData();
            $categorieId = $categorieRepository->find($request->request->get('categories'));
            $serie->setCategorieId($categorieId);
            
            // $image = $serie->getImage();
            $image = $serieForm->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_files'), 
            $imageName);
            $serie->setImage($imageName);

            
            $entityManager->persist($serie);
            $entityManager->flush();

            return $this->redirectToRoute('serie');

        }

        dump($series);

        return $this->render('serie/index.html.twig', [
            'serieForm' => $serieForm->createView(),
            'categories' => $allCategories,
            'series' => $series
        ]);
    }

    /**
     * @Route("/serie/{id}", name="fiche")
     */
    public function selectedSerie($id, Request $request, EntityManagerInterface $entityManager) {


        $serie = $this->getDoctrine()->getRepository(Serie::class)->find($id);

        $categorieRepository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $categorieRepository->findAll();

        $updateSerieForm = $this->createForm(SerieType::class, $serie);
        $updateSerieForm->handleRequest($request);

        // $serie->setImage(new File($this->getParameter('upload_files').'/'.$serie->getImage()));

        return $this->render('serie/fiche.html.twig', [
            'serie' => $serie,
            'serieForm' => $updateSerieForm->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/removeserie/{id}", name="removeserie")
     */
    public function remove($id, Request $request, EntityManagerInterface $entityManager){
        $serie = $this->getDoctrine()->getRepository(Serie::class)->find($id);

        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('home');

    }
}
