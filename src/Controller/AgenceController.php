<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AgenceController extends AbstractController
{
    #[Route('/agence/new', name: 'agence_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('agence_list');
        }

        return $this->render('agence/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/agences', name: 'agence_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $agences = $entityManager->getRepository(Agence::class)->findAll();

        return $this->render('agence/index.html.twig', [
            'agences' => $agences,
        ]);
    }
    #[Route('/agence/{id}/edit', name: 'agence_edit')]
    public function edit(Request $request, Agence $agence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('agence_list');
        }

        return $this->render('agence/edit.html.twig', [
            'form' => $form->createView(),
            'agence' => $agence,
        ]);
    }


    #[Route("/agence/{id}/delete", name: "agence_delete")]
    //  #[Route("/delete/{id}",name:"app_delete_author")]
    public function deleteagence($id, AgenceRepository $repository, ManagerRegistry $doctrine):Response
    {
        //get the object from the database
        $agence=$repository->find($id);
        //2.crate a copy of the doctrine with entityManager: $em
        //2.a: use Doctrine\Persistence\ManagerRegistry;
        $em=$doctrine->getManager();
        //3. remove the object from the doctrine layer
        $em->remove($agence);
        //4. save the updates in the database
        $em->flush();
        return $this->redirectToRoute("agence_list");
    }




}