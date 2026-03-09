<?php

namespace App\Controller;

use App\Entity\Transactions;
use App\Entity\Users;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    #[Route('/overzicht/add/transaction/{id}', name: 'app_overview_transaction_add')]
    public function addTransaction(Request $request, EntityManagerInterface $entityManager, $id): Response
    {   $user = $entityManager->getRepository(Users::class)->find($id);
        $form = $this->createForm(TransactionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = $form->getData();
            $transaction->setUser($user);
            $entityManager->persist($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'de Transactie is toegevoegd');
            // Add timestamp to force page reload and prevent caching
            return $this->redirectToRoute('user_detail', [
                'id' => $id,
                '_fragment' => 'myChart',
            ]);
        }

        return $this->render('overview/add.html.twig', [
            'form' => $form,
            'formText' => 'Voeg een transactie toe',
            'user' => $user,
            'type' => 'add',
        ]);
    }


    #[Route('/overzicht/transaction/delete/{id}/{userId}', name: 'transaction_delete')]
    public function deleteTransaction(
        EntityManagerInterface $entityManager,
        int $id,
        int $userId
    ): Response {
        $transaction = $entityManager->getRepository(Transactions::class)->find($id);

        if ($transaction) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        // Add timestamp to force page reload and prevent caching
        return $this->redirectToRoute('user_detail', [
            'id' => $userId,
            't' => time()
        ]);
    }
    #[Route('/overzicht/transaction/delete/{id}', name: 'app_choose_transaction_delete')]
    public function ChooseDeleteTransaction(
        EntityManagerInterface $entityManager,
        int $id,
    ): Response {
        $user = $entityManager->getRepository(Users::class)->find($id);
        $transactions = $user->getTransactions();
        return $this->render('overview/delete-transaction.html.twig', [
            'controller_name' => 'ContactsController',
            'transactions' => $transactions,
            'user' => $user,
        ]);
    }
    #[Route('/overzicht/transaction/details/{id}', name: 'app_show_transaction_details')]
    public function DetailsTransaction(EntityManagerInterface $entityManager, int $id): Response {
        $transaction = $entityManager->getRepository(Transactions::class)->find($id);
        return $this->render('overview/transaction-detail.html.twig', [
            'controller_name' => 'ContactsController',
            'transaction' => $transaction,
            'user' => $transaction->getUser()
        ]);
    }

    #[Route('/overzicht/transaction/edit/{id}/{userId}', name: 'transaction_edit')]
    public function editTransaction(EntityManagerInterface $entityManager, int $id, int $userId, Request $request): Response {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        $transaction = $entityManager->getRepository(Transactions::class)->find($id);
        $form = $this->createForm(TransactionType::class);
        $form->setData($transaction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $transaction->setCategory($form->getData()->getCategory());
            $transaction->setAmount($form->getData()->getAmount());
            $transaction->setTransactionDate($form->getData()->getTransactionDate());
            $transaction->setDescription($form->getData()->getDescription());
            $entityManager->persist($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'de transactie is gewijzigd');
            return $this->redirectToRoute('user_detail', ['id' => $userId]);
        }
        return $this->render('overview/add.html.twig', [
            'form' => $form,
            'formText' => 'wijzig transactie: ' . $transaction->getDescription(),
            'type' => 'edit',
            'user' => $user,
            'item' => $transaction,
        ]);
    }
    #[Route('/overzicht/transaction/show/{id}/{type}', name: 'app_show_transaction')]
    public function showTransaction(EntityManagerInterface $entityManager,$id, $type): Response {
        $user = $entityManager->getRepository(Users::class)->find($id);
        $transactions = $user->getTransactions();
        return $this->render('overview/show-transactions.html.twig', [
            'controller_name' => 'ContactsController',
            'transactions' => $transactions,
            'type' => $type,
            'user' => $user,
        ]);
    }
}
