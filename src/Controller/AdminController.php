<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModificationType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    Private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin")
     */
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request): Response

    {
        $user = new User();
        $addUser = $this->createForm(UserType::class, $user);
        $addUser->handleRequest($request);

        if($addUser->isSubmitted() && $addUser->isValid()) {
            $user = $addUser->getData();
            $roles=$addUser->get("choix")->getData();
            $user->setRoles([$roles]);
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin');
        }

        $users = $this->entityManager->getRepository(User::class)->findAll();
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',

            'user' => $users,
            'add_user' => $addUser->createView()
        ]);
    }
    /**
     * @Route("/edit/admin/{id}", name="edit_admin")
     */

    public function edit(User $user,Request $request,EntityManagerInterface $manager):Response
    {
        $addForm = $this->createForm(ModificationType:: class, $user);
        $addForm->handleRequest($request);


        if($addForm->isSubmitted() && $addForm->isValid()) {
            $user = $addForm->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute("admin");
        }

        return $this->render('admin/modit.html.twig', [
            'user' => $user,
            'add_user'=>$addForm->createView(),

        ]);


    }
    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function delete(User $user,Request $request,EntityManagerInterface $manager):Response
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove([$this->getParameter('documents_directory') . '/' . $user->getId()]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin');



    }
}
