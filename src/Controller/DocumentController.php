<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentType;
use Couchbase\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DocumentController extends AbstractController

{
    Private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/document", name="document")
     */
    public function index(): Response
    {
        $documents =$this->entityManager->getRepository(Documents::class)->findAll();
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }
    /**
     * @Route("/document/new", name="document_new")
     */
    public function new(Request $request, SluggerInterface $slugger)
    {
        $documents = new documents();
        $form = $this->createForm(DocumentType::class, $documents);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $documentsFile */
            $documentsFile = $form->get('document')->getData();


            if ($documentsFile) {
                $originalFilename = pathinfo($documentsFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$documentsFile->guessExtension();


                try {
                    $documentsFile->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

            }
            $documents->setNom($newFilename);
            $this->entityManager->persist($documents);
            $this->entityManager->flush();
            return $this->redirectToRoute('document_new');
        }
        $documents =$this->entityManager->getRepository(Documents::class)->findAll();

        return $this->render('document/document.html.twig', [
            'documents'=> $documents,
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/delete/{id}", name="document_delete")
     */
    public function delete(Documents $documents,Request $request,EntityManagerInterface $manager):Response
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove([$this->getParameter('documents_directory') . '/' . $documents->getNom()]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($documents);
        $entityManager->flush();

        return $this->redirectToRoute('document_new');



    }
}
