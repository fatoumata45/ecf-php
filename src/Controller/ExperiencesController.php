<?php

namespace App\Controller;

use App\Entity\Experiences;
use App\Form\ExperiencesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExperiencesController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/experiences", name="experiences")
     */
    public function index(): Response
    {
        $experiences =$this->entityManager->getRepository(Experiences::class)->findAll();
        return $this->render('experiences/index.html.twig', [
            'controller_name' => 'ExperiencesController',
        ]);
    }
    /**
     * @Route("/experiences/add", name="add_experiences")
     */

    public function addExperiences(Request $request): Response
    {
        $experiences = new Experiences();
        $addForm = $this->createForm(ExperiencesType:: class, $experiences);
        $addForm->handleRequest($request);


        if($addForm->isSubmitted() && $addForm->isValid()) {
            $experiences = $addForm->getData();
            $this->entityManager->persist($experiences);
            $this->entityManager->flush();
        }
        $experiences = $this->entityManager->getRepository(Experiences::class)->findAll();
        return $this->render('experiences/add.html.twig', [
            'expeiences'=> $experiences,
            'add_form'=>$addForm->createView(),

        ]);
    }
    /**
     * @Route("/edit/experiences/{id}", name="edit_experiences")
     */

    public function edit(Experiences $experiences,Request $request,EntityManagerInterface $manager):Response
    {
     $addForm = $this->createForm(ExperiencesType:: class, $experiences);
        $addForm->handleRequest($request);


        if($addForm->isSubmitted() && $addForm->isValid()) {
            $experiences = $addForm->getData();
            $this->entityManager->persist($experiences);
            $this->entityManager->flush();
            return $this->redirectToRoute("add_experiences");
        }

return $this->render('experiences/edit.html.twig', [
    'add_form'=>$addForm->createView(),

]);
}



}


