<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie/{id}", name="categorie")
     */
    public function index($id, Request $request, EntityManagerInterface $entityManager)
    {

        // $categorie = new Categorie;

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);

        $formUpdateCategorie = $this->createForm(CategorieType::class, $categorie);
        $formUpdateCategorie->handleRequest($request);

        if($formUpdateCategorie->isSubmitted() && $formUpdateCategorie->isValid())
        {

            $categorie = $formUpdateCategorie->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }

        return $this->render('categorie/index.html.twig', [
            'categorie' => $categorie,
            'updateFormCategorie' => $formUpdateCategorie->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="removeCategorie")
     */

    public function remove($id, EntityManagerInterface $entityManager){
        
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);

        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    
    }
}
