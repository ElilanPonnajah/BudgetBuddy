<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/overzicht/add/user', name: 'app_overview_add')]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'de user is toegevoegd');

            return $this->redirectToRoute('app_overview');
        }

        return $this->render('overview/add.html.twig', [
            'form' => $form,
            'formText' => 'Voeg een gebruiker toe',
            'type' => 'add',
            'user' => null,
        ]);

        }
    #[Route('/overzicht/del/user/{id}', name: 'app_overview_delete')]
    public function deleteUser(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'de user is verwijderd!');


        return $this->redirectToRoute('app_overview');
    }
}
