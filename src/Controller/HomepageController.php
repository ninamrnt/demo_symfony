<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Usager;

class HomepageController extends AbstractController
{
    /**
     * @Route("/homepage", name="homepage")
     */
    public function homepage()
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
    
    
    /**
    * @Route("/usager/{nom}", name="usager")
    */
    public function usager($nom)
    {
        
        $usager = $this->getDoctrine()
        ->getRepository(Usager::class)
        ->findOneByNom($nom);

        if (!$usager) 
            throw $this->createNotFoundException('Usager inconnu');
        
        dump($usager);

        return $this->render('homepage/usager.html.twig', [
            'usager' => $usager,
        ]);
    }

    /**
     * @Route("/ajouterUsager", name="ajouterUsager")
     */
    public function ajouterUsager(Request $request)
    {
        $usager = new Usager();
        $form = $this->createFormBuilder($usager)
        ->add('nom')
        ->add('prenom')
        ->add('adresse',TextareaType::class)
        ->add('send', SubmitType::class)
        ->getForm();

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {
                // you can fetch the EntityManager via $this->getDoctrine()
                // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
                $entityManager = $this->getDoctrine()->getManager();
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($usager);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
            }
        }
        return $this->render('ajouterUsager.html.twig',array('form' => $form->createView()));
    }
}
