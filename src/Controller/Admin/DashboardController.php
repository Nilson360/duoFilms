<?php
// src/Controller/Admin/DashboardController.php
namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Photo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'admin_dashboard')]
    public function index(): Response
    {
        $photoCount = $this->entityManager->getRepository(Photo::class)->count([]);
        $messageCount = $this->entityManager->getRepository(Contact::class)->count([]);
        $userCount = $this->entityManager->getRepository(User::class)->count([]);

        return $this->render('admin/dashboard/index.html.twig', [
            'photoCount' => $photoCount,
            'messageCount' => $messageCount,
            'userCount' => $userCount,
        ]);
    }
    #[Route('/users', name: 'admin_messages')]
    public function messages(): Response
    {
        $photoCount = $this->entityManager->getRepository(Photo::class)->count([]);
        $messageCount = $this->entityManager->getRepository(Contact::class)->count([]);
        $userCount = $this->entityManager->getRepository(User::class)->count([]);

        return $this->render('admin/dashboard/index.html.twig', [
            'photoCount' => $photoCount,
            'messageCount' => $messageCount,
            'userCount' => $userCount,
        ]);
    }
    #[Route('/users', name: 'admin_users')]
    public function users(): Response
    {
        $photoCount = $this->entityManager->getRepository(Photo::class)->count([]);
        $messageCount = $this->entityManager->getRepository(Contact::class)->count([]);
        $userCount = $this->entityManager->getRepository(User::class)->count([]);

        return $this->render('admin/dashboard/index.html.twig', [
            'photoCount' => $photoCount,
            'messageCount' => $messageCount,
            'userCount' => $userCount,
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        $photoCount = $this->entityManager->getRepository(Photo::class)->count([]);
        $messageCount = $this->entityManager->getRepository(Contact::class)->count([]);
        $userCount = $this->entityManager->getRepository(User::class)->count([]);

        return $this->render('admin/dashboard/index.html.twig', [
            'photoCount' => $photoCount,
            'messageCount' => $messageCount,
            'userCount' => $userCount,
        ]);
    }
}