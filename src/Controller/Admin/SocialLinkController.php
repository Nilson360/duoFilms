<?php

namespace App\Controller\Admin;

use App\Entity\SocialLink;
use App\Form\SocialLinkForm;
use App\Repository\SocialLinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/social/link')]
class SocialLinkController extends AbstractController
{
    #[Route(name: 'app_social_link_index', methods: ['GET'])]
    public function index(SocialLinkRepository $socialLinkRepository): Response
    {
        return $this->render('admin/social_link/index.html.twig', [
            'socialLinks' => $socialLinkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_social_link_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $socialLink = new SocialLink();
        $form = $this->createForm(SocialLinkForm::class, $socialLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($socialLink);
            $entityManager->flush();

            return $this->redirectToRoute('app_social_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/social_link/new.html.twig', [
            'socialLinks' => $socialLink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_social_link_show', methods: ['GET'])]
    public function show(SocialLink $socialLink): Response
    {
        return $this->render('admin/social_link/show.html.twig', [
            'socialLinks' => $socialLink,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_social_link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocialLink $socialLink, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocialLinkForm::class, $socialLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_social_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/social_link/edit.html.twig', [
            'socialLinks' => $socialLink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_social_link_delete', methods: ['POST'])]
    public function delete(Request $request, SocialLink $socialLink, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$socialLink->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($socialLink);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_social_link_index', [], Response::HTTP_SEE_OTHER);
    }
}
