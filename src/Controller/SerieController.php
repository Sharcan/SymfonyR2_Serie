<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Serie;
use App\Form\SerieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    /**
     * @Route("/serie", name="serie")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)  
    {

        $serie = new Serie();


        $categorieRepository = $this->getDoctrine()->getRepository(Categorie::class);
        $allCategories = $categorieRepository->findAll();
        


        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);


        if($serieForm->isSubmitted() && $serieForm->isValid()){

            $serie = $serieForm->getData();
            $categorieId = $categorieRepository->find($request->request->get('categories'));
            $serie->setCategorieId($categorieId);
            
            $image = $serie->getImage();
            dump(md5(uniqid()).'.'.$image);
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_files'), 
            $imageName);
            $serie->setImage($imageName);

            
            $entityManager->persist($serie);
            $entityManager->flush();

        }


        return $this->render('serie/index.html.twig', [
            'serieForm' => $serieForm->createView(),
            'categories' => $allCategories
        ]);
    }

}
