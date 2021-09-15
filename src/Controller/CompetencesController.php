<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Form\CompetencesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetencesController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/competences", name="competences")
     */
    public function index(): Response
    {
        $competences = $this->entityManager->getRepository(Competences::class)->findAll();

        return $this->render('competences/index.html.twig', [
            'controller_name' => 'CompetencesController',
        ]);
    }
    /**
     * @Route("/competences/add", name="add_competences")
     */
    public function addCompetences(Request $request): Response
    {
        $competences = new Competences();
        $addForm = $this->createForm(CompetencesType:: class, $competences);
        $addForm->handleRequest($request);


        if($addForm->isSubmitted() && $addForm->isValid()) {
            $competences = $addForm->getData();
            $this->entityManager->persist($competences);
            $this->entityManager->flush();
        }
        $competences = $this->entityManager->getRepository(Competences::class)->findAll();
        return $this->render('competences/add.html.twig', [
            'competences'=> $competences,
            'add_form'=>$addForm->createView(),

        ]);
    }
    /**
     * @Route("/edit/competences/{id}", name="edit_competences")
     */

    public function edit(Competences $competences,Request $request,EntityManagerInterface $manager):Response
    {
        $addForm = $this->createForm(CompetencesType:: class, $competences);
        $addForm->handleRequest($request);


        if($addForm->isSubmitted() && $addForm->isValid()) {
            $competences = $addForm->getData();
            $this->entityManager->persist($competences);
            $this->entityManager->flush();
            return $this->redirectToRoute("add_competences");
        }
        return $this->render('competences/edit.html.twig', [
            'add_form'=>$addForm->createView(),

        ]);
    }
}
