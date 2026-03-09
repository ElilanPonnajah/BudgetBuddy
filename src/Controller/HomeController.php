<?php

namespace App\Controller;

use App\Entity\Catagories;
use App\Entity\Transactions;
use App\Entity\Users;
use App\Form\CategoryType;
use App\Form\TransactionType;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use PhpParser\Node\Stmt\Else_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\CategoryDefaults;



final class HomeController extends AbstractController
{


    #[Route('/home', name: 'app_home')]
    #[Route('/', name: 'app_home_alt')]
    public function Home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'welcome_message' => 'Salam alaikum!',
            'intro_text' => 'Welkom bij de project Budget Buddy',
        ]);
    }
    #[Route('/Sparen', name: 'app_savings')]
    public function Savings(): Response
    {
        return $this->render('savings/index.html.twig', [
            'controller_name' => 'SavingsController',
        ]);
    }
    #[Route('/overzicht', name: 'app_overview')]
    public function Overview(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Users::class)->findAll();
        return $this->render('overview/index.html.twig', [
            'controller_name' => 'OverviewController',
            'users' => $users,
        ]);
    }
    #[Route('/overzicht/user/{id}', name: 'user_detail')]
    public function OverviewDetail(EntityManagerInterface $entityManager, CategoryDefaults $categoryDefaults, int $id): Response
    {
        // Ensure global default categories exist (user = null) whenever a user detail is opened
        $categoryDefaults->ensureDefaultCategories($entityManager);

        $user = $entityManager->getRepository(Users::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Gebruiker niet gevonden');    // Als de gebruiker niet bestaat → geeft een foutmelding (“Gebruiker niet gevonden”).
        }

        $users = $entityManager->getRepository(Users::class)->findAll();

        // Convert Doctrine Collection to array and manually serialize for JSON
     // De website krijgt een id (bijv. 3) uit de link.
    // Zoekt in de database naar de gebruiker met dat id.

    // Als de gebruiker bestaat → haalt al zijn transacties (inkomsten/uitgaven) op.

     // Laat daarna een pagina zien met alle details van die gebruiker en zijn transacties.




        $transactions = $user->getTransactions();
        $custom_categories = $user->getCatagories();
        $default_categories = $entityManager->getRepository(Catagories::class)->findBy(['user' => null]);

        return $this->render('overview/detail.html.twig', [
            'controller_name' => 'DetailOverviewController',
            'users' => $users,
            'user' => $user,
            'transactions' => $transactions, // Use original collection for Twig loops
            'transactionsJson' => $transactions, // Use serialized version for JavaScript
            'custom_categories' => $custom_categories,
            'default_categories' => $default_categories,
        ]);
    }
    #[Route('/contacten', name: 'app_contacts')]
    public function Contacts(): Response
    {
        return $this->render('contacts/index.html.twig', [
            'controller_name' => 'ContactsController',
        ]);
    }
    #[Route('/grafieken', name: 'app_charts')]
    public function Charts(): Response
    {
        return $this->render('charts/index.html.twig', [
            'controller_name' => 'ChartsController',
        ]);
    }
    #[Route('/over-ons', name: 'app_about_us')]
    public function AboutUs(): Response
    {
        return $this->render('about_us/index.html.twig', [
            'controller_name' => 'AboutUsController',
        ]);
    }
    #[Route('/advies', name: 'app_advise')]
    public function Advise(EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager->getRepository(Users::class)->findAll();
        return $this->render('advise/index.html.twig', [
            'controller_name' => 'adviseController',
            'users' => $users,
        ]);
    }
    #[Route('/advies/{id}', name: 'app_advise_detail')]
    public function detailAdvise(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id);
        $users = $entityManager->getRepository(Users::class)->findAll();
        return $this->render('advise/detail.html.twig', [
            'controller_name' => 'adviseController',
            'user' => $user,
            'users' => $users,
        ]);
    }


}
