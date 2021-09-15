<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntreprisesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntreprisesController extends AbstractController
{
    Private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/entreprises", name="entreprises")
     */
    public function index(): Response
    {
        $entreprises = $this->entityManager->getRepository(Entreprises::class)->findAll();



        return $this->render('entreprises/index.html.twig', [
            'controller_name' => 'EntreprisesController',
        ]);
    }


    /**
     * @Route("/entreprises/add", name="add_entreprises")
     */
    public function addEntreprises(Request $request): Response
    {
        $entreprises = new Entreprises();

        $addForm = $this->createForm(EntreprisesType::class, $entreprises);
        $addForm->handleRequest($request);

        if($addForm->isSubmitted() && $addForm->isValid()) {
            $entreprises = $addForm->getData();
            $this->entityManager->persist($entreprises);
            $this->entityManager->flush();
        }
        $entreprises = $this->entityManager->getRepository(Entreprises::class)->findAll();
        return $this->render('entreprises/add.html.twig', [
            'entreprises'=> $entreprises,
            'add_form'=>$addForm->createView(),

        ]);
    }
    /**
     * @Route("/edit/entreprises/{id}", name="edit_entreprises")
     */

    public function edit(Entreprises $entreprises,Request $request,EntityManagerInterface $manager):Response
    {
        $addForm = $this->createForm(EntreprisesType:: class, $entreprises);
        $addForm->handleRequest($request);


        if($addForm->isSubmitted() && $addForm->isValid()) {
            $entreprises = $addForm->getData();
            $this->entityManager->persist($entreprises);
            $this->entityManager->flush();
            return $this->redirectToRoute("add_entreprises");
        }
        return $this->render('entreprises/edit.html.twig', [
            'add_form'=>$addForm->createView(),

        ]);
    }
}
