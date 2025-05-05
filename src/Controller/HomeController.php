<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\MailerService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;
    private KernelInterface $kernel;
    private MailerService $mailerService;
    public function __construct(EntityManagerInterface $em, KernelInterface $kernel, MailerService $mailerService)
    {
        $this->em = $em;
        $this->kernel = $kernel;
        $this->mailerService = $mailerService;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contactEntity = new Contact();
        $contactEntity->setCreatedAt(new DateTimeImmutable());

        $form = $this->createForm(ContactFormType::class, $contactEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contactEntity);
            $entityManager->flush();

            $this->addFlash('success', 'A sua mensagem foi enviada com sucesso!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/portofolio', name: 'app_portfolio')]
    public function portfolio(): Response
    {
        return $this->render('portfolio/index.html.twig', [
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Contact();
        $message->setCreatedAt(new DateTimeImmutable());

        $form = $this->createForm(ContactFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Sua mensagem foi enviada com sucesso!');

            return $this->redirectToRoute('contact');
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/details', name: 'app_portfolio_details')]
    public function portfolioDetails(): Response
    {
        // Simulação de dados do projeto para testes
        $projeto = [
            'title' => 'Casamento de Maria e João',
            'slug' => 'casamento-maria-joao',
            'coverImage' => 'img_5.png',
            'categoria' => 'Casamentos',
            'description' => 'Uma celebração elegante no coração de Lisboa, repleta de momentos emocionantes e detalhes encantadores.',
            'detailedDescription' => 'Este casamento foi realizado em uma das mais belas quintas de Lisboa, com uma vista deslumbrante para o rio Tejo. A cerimônia aconteceu ao pôr do sol, criando um ambiente mágico para os noivos e convidados. Nossa equipe capturou cada momento, desde os preparativos da noiva até a última dança da festa.
            
            Trabalhamos com uma paleta de cores suaves e uma iluminação natural que realçou a beleza do local e dos participantes. O resultado foi uma coleção de imagens que transmitem não apenas a elegância do evento, mas também a emoção e alegria deste dia especial.',
            'data' => '15 de Junho, 2024',
            'local' => 'Quinta do Lago, Lisboa',
            'totalFotos' => 50,
            'cliente' => 'Maria Silva & João Santos',
            'clienteInfo' => 'Maria e João são um casal jovem de Lisboa que queria um casamento elegante, mas com toques pessoais e íntimos. Eles nos deram total liberdade criativa para capturar sua história de amor e os momentos mais importantes do seu grande dia.',
            'clienteDepoimento' => 'A equipe da Duo Films capturou perfeitamente cada momento do nosso casamento. As fotos transmitem toda a emoção e alegria do dia. Simplesmente incrível!',
            'clienteNome' => 'Maria Silva',
            'clienteCargo' => 'Noiva',
            'clienteAvatar' => null,
            'equipe' => ['Carlos Oliveira', 'Ana Sousa', 'Pedro Ferreira'],

            // Fotos do projeto
            'fotos' => [
                [
                    'url' => 'img_5.png',
                    'alt' => 'Casal na cerimônia',
                    'categoria' => 'Cerimônia'
                ],
                [
                    'url' => 'gallery/img-1.jpg',
                    'alt' => 'Preparativos da noiva',
                    'categoria' => 'Preparativos'
                ],
                [
                    'url' => 'gallery/img-2.jpg',
                    'alt' => 'Detalhes das alianças',
                    'categoria' => 'Detalhes'
                ],
                [
                    'url' => 'gallery/img-3.jpg',
                    'alt' => 'Entrada da noiva',
                    'categoria' => 'Cerimônia'
                ],
                [
                    'url' => 'gallery/img-4.jpg',
                    'alt' => 'Primeiro beijo como casados',
                    'categoria' => 'Cerimônia'
                ],
                [
                    'url' => 'gallery/img-5.jpg',
                    'alt' => 'Celebração com convidados',
                    'categoria' => 'Recepção'
                ],
                [
                    'url' => 'gallery/img-6.jpg',
                    'alt' => 'Decoração do local',
                    'categoria' => 'Detalhes'
                ],
                [
                    'url' => 'gallery/img-7.jpg',
                    'alt' => 'Dança dos noivos',
                    'categoria' => 'Recepção'
                ],
                [
                    'url' => 'gallery/img-8.jpg',
                    'alt' => 'Retrato do casal ao pôr do sol',
                    'categoria' => 'Ensaio'
                ],
                [
                    'url' => 'img_5.png',
                    'alt' => 'Brinde dos padrinhos',
                    'categoria' => 'Recepção'
                ],
                [
                    'url' => 'gallery/img-1.jpg',
                    'alt' => 'Sessão de fotos no jardim',
                    'categoria' => 'Ensaio'
                ],
                [
                    'url' => 'gallery/img-2.jpg',
                    'alt' => 'Corte do bolo',
                    'categoria' => 'Recepção'
                ],
            ],

            // Vídeos do projeto
            'videos' => [
                [
                    'url' => 'https://www.youtube.com/embed/h_H-oIWUJmY?si=qAS8oWyKSisxKOoi',
                    'title' => 'Highlights do Casamento',
                    'description' => 'Um resumo dos momentos mais marcantes do casamento de Maria e João.'
                ],
                [
                    'url' => 'https://www.youtube.com/embed/IL879JU1Duo?si=GfmcMBPemkWtEM66',
                    'title' => 'Cerimônia Completa',
                    'description' => 'Gravação da cerimônia de casamento completa.'
                ],
                [
                    'url' => 'https://www.youtube.com/embed/d5nZ8RhB2pI?si=3WuHq8eSrITlfYj_',
                    'title' => 'Ensaio Pré-Casamento',
                    'description' => 'Sessão fotográfica realizada na semana anterior ao casamento.'
                ]
            ],

            // Equipamentos utilizados
            'equipamentos' => [
                [
                    'nome' => 'Canon EOS R5',
                    'tipo' => 'Camera'
                ],
                [
                    'nome' => 'Sony A7S III',
                    'tipo' => 'Camera'
                ],
                [
                    'nome' => 'DJI Ronin S',
                    'tipo' => 'Estabilizador'
                ],
                [
                    'nome' => 'Canon RF 50mm f/1.2',
                    'tipo' => 'Lente'
                ],
                [
                    'nome' => 'Sony 24-70mm f/2.8',
                    'tipo' => 'Lente'
                ],
                [
                    'nome' => 'Godox AD600Pro',
                    'tipo' => 'Iluminação'
                ],
            ],

            // Outros projetos do mesmo cliente
            'clienteOutrosProjetos' => [
                [
                    'title' => 'Ensaio Pré-Casamento',
                    'slug' => 'ensaio-pre-casamento-maria-joao',
                    'coverImage' => 'gallery/img-8.jpg',
                    'data' => '1 de Junho, 2024'
                ],
            ]
        ];

        // Projetos relacionados
        $projetosRelacionados = [
            [
                'title' => 'Casamento de Ana e Pedro',
                'slug' => 'casamento-ana-pedro',
                'coverImage' => 'gallery/img-3.jpg',
                'categoria' => 'Casamentos'
            ],
            [
                'title' => 'Casamento de Sofia e Miguel',
                'slug' => 'casamento-sofia-miguel',
                'coverImage' => 'gallery/img-4.jpg',
                'categoria' => 'Casamentos'
            ],
            [
                'title' => 'Casamento na Praia',
                'slug' => 'casamento-praia-rita-tiago',
                'coverImage' => 'gallery/img-7.jpg',
                'categoria' => 'Casamentos'
            ]
        ];

        return $this->render('portfolio/details.html.twig', [
            'projeto' => $projeto,
            'projetosRelacionados' => $projetosRelacionados,
            'title' => 'test'
        ]);
    }

    #[Route('/{slug}', name: 'app_footer_content', requirements: ['slug' => 'about|privacy'], methods: ['GET'])]
    public function footerContent(string $slug): Response
    {
        $templates = [
            'about' => ['template' => 'about.html.twig', 'title' => 'Modo Offline'],
            'privacy' => ['template' => 'privacy.html.twig', 'title' => 'Kitadi Connect demonstração'],
        ];

        if (!isset($templates[$slug])) {
            throw $this->createNotFoundException('Page d’information non trouvée.');
        }

        return $this->render($templates[$slug]['template'], [
            'pageTitle' => $templates[$slug]['title'],
        ]);
    }
}