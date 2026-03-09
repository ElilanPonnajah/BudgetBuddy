<?php

namespace App\Controller;

use App\Entity\Catagories;
use App\Entity\Transactions;
use App\Entity\Users;
use App\Form\CategoryType;
use App\Form\EditCategoryType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CategoryDefaults;

final class CategoryController extends AbstractController
{
    public function addDefaultCategory(Request $request, EntityManagerInterface $entityManager, CategoryDefaults $defaults, $id): Response
    {
        // Ensure the global default categories (user = null) exist once
        $created = $defaults->ensureDefaultCategories($entityManager);
        if ($created > 0) {
            $this->addFlash('success', 'De standaardcategorieën zijn toegevoegd.');
        }
        return $this->redirectToRoute('user_detail', ['id' => $id]);
    }
    #[Route('/overzicht/category/show/{id}/{userId}', name: 'app_overview_category_show')]
    public function categoryDetail(Request $request, EntityManagerInterface $entityManager, $id, $userId): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($userId);

        $category = $entityManager->getRepository(Catagories::class)->find($id);

        $transactions = $entityManager->getRepository(Transactions::class)->findBy([
            'category' => $id,
            'user' => $userId
        ]);

        return $this->render('overview/category.html.twig', [
            'categoryText' => $category->getName(),
            'transactions' => $transactions,
            'user' => $user,
        ]);
    }

    #[Route('/overzicht/add/category/{id}', name: 'app_overview_category_add')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id); // << DEZE REGEL
        $form = $this->createForm(CategoryType::class); // Maakt de form om de categorie toe te voegen
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { // Checkt of de form is gesubmit  en of de form juiste gegevens bevat
            $category = $form->getData();
            $category->SetUser($user);  // << DEZE REGEL // hier word de category gelinkt aan de user
            $entityManager->persist($category); // hier wordt de categorie aangemaakt in de database
            $entityManager->flush();

            $this->addFlash('success', 'de Transactie is toegevoegd');
            return $this->redirectToRoute('user_detail', ['id' => $id]);
        }

        return $this->render('overview/add.html.twig', [
            'form' => $form,
            'formText' => 'Voeg een categorie toe',
            'user' => $user,
            'type' => 'add',
        ]);
    }
    #[Route('/overzicht/category/delete/{id}/{userId}', name: 'category_delete')]
    public function deleteCategory(
        EntityManagerInterface $entityManager,
        int $id,
        int $userId
    ): Response {
        $category = $entityManager->getRepository(Catagories::class)->find($id);

        if ($category) {
            $entityManager->remove($category);
            $entityManager->flush();
        }


        return $this->redirectToRoute('user_detail', ['id' => $userId]);
    }
    #[Route('/overzicht/category/edit/{id}', name: 'app_choose_category_edit')]
    public function ChooseEditCategory(
        EntityManagerInterface $entityManager,
        int $id,
    ): Response {
        $user = $entityManager->getRepository(Users::class)->find($id);
        $custom_categories = $user->getCatagories();
        $default_categories = $entityManager->getRepository(Catagories::class)->findBy(['user' => null]);
        return $this->render('overview/edit-category.html.twig', [
            'controller_name' => 'ContactsController',
            'custom_categories' => $custom_categories,
            'default_categories' => $default_categories,
            'user' => $user,
        ]);
    }
    #[Route('/overzicht/category/edit/{id}/{userId}', name: 'category_edit')]
    public function editCategory(EntityManagerInterface $entityManager, int $id, int $userId, Request $request): Response {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        $category = $entityManager->getRepository(Catagories::class)->find($id);
        $form = $this->createForm(EditCategoryType::class);
        $form->setData($category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                $category->setName($form->getData()->getName());
                $category->setIcon($form->getData()->getIcon());
                $entityManager->persist($category);
                $entityManager->flush();

                $this->addFlash('success', 'de categorie is gewijzigd');
                return $this->redirectToRoute('user_detail', ['id' => $userId]);
        }
        return $this->render('overview/add.html.twig', [
            'form' => $form,
            'formText' => 'wijzig categorie: ' . $category->getName(),
            'type' => 'edit',
            'user' => $user,
            'item' => $category,
        ]);
    }
}
