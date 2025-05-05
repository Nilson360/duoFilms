<?php
// src/Controller/Admin/MultimediaController.php
namespace App\Controller\Admin;

use App\Entity\Photo;
use App\Form\PhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/multimedia')]
class MultimediaController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('/', name: 'admin_multimedia')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $repository = $this->entityManager->getRepository(Photo::class);
        $photos = $repository->findBy([], ['createAt' => 'DESC'], $limit, $offset);
        $totalPhotos = $repository->count([]);
        $totalPages = ceil($totalPhotos / $limit);

        return $this->render('admin/multimedia/index.html.twig', [
            'photos' => $photos,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'admin_multimedia_new')]
    public function new(Request $request): Response
    {
        $photo = new Photo();
        $photo->setCreateAt(new \DateTimeImmutable());

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );

                    $photo->setPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Ocorreu um erro ao carregar a imagem.');
                    return $this->redirectToRoute('admin_multimedia_new');
                }
            }

            $this->entityManager->persist($photo);
            $this->entityManager->flush();

            $this->addFlash('success', 'Fotografia adicionada com sucesso.');
            return $this->redirectToRoute('admin_multimedia');
        }

        return $this->render('admin/multimedia/form.html.twig', [
            'form' => $form->createView(),
            'photo' => $photo,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_multimedia_edit')]
    public function edit(Request $request, Photo $photo): Response
    {
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );

                    // Remover imagem antiga se existir
                    $oldPath = $photo->getPath();
                    if ($oldPath) {
                        $oldFilePath = $this->getParameter('photos_directory') . '/' . $oldPath;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    $photo->setPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Ocorreu um erro ao carregar a imagem.');
                    return $this->redirectToRoute('admin_multimedia_edit', ['id' => $photo->getId()]);
                }
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Fotografia atualizada com sucesso.');
            return $this->redirectToRoute('admin_multimedia');
        }

        return $this->render('admin/multimedia/form.html.twig', [
            'form' => $form->createView(),
            'photo' => $photo,
        ]);
    }

    #[Route('/{id}', name: 'admin_multimedia_show')]
    public function show(Photo $photo): Response
    {
        return $this->render('admin/multimedia/show.html.twig', [
            'photo' => $photo,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_multimedia_delete', methods: ['POST'])]
    public function delete(Request $request, Photo $photo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            // Remover imagem do sistema de arquivos
            $path = $photo->getPath();
            if ($path) {
                $filePath = $this->getParameter('photos_directory') . '/' . $path;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->entityManager->remove($photo);
            $this->entityManager->flush();

            $this->addFlash('success', 'Fotografia eliminada com sucesso.');
        }

        return $this->redirectToRoute('admin_multimedia');
    }
}