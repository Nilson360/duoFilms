<?php

namespace App\Form;

use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PageForm extends AbstractType
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, insira um título']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'O título deve ter pelo menos {{ limit }} caracteres',
                        'max' => 255,
                        'maxMessage' => 'O título deve ter no máximo {{ limit }} caracteres'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Ex: Sobre Nós'
                ]
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Publicar página',
                'required' => false,
                'data' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Conteúdo',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, insira o conteúdo da página']),
                ],
                'attr' => [
                    'rows' => 15,
                    'placeholder' => 'Escreva o conteúdo da página aqui...',
                    'class' => 'tinymce',
                    'id' => 'wysiwyg-editor'
                ],
                'help' => 'Use o editor para formatar o conteúdo'
            ]);

        // Evento para gerar slug automaticamente
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $page = $event->getData();
            $form = $event->getForm();

            // Se o slug não foi preenchido, gera-o a partir do título
            if (!$page->getSlug() && $page->getTitle()) {
                $slug = $this->slugger->slug(mb_strtolower($page->getTitle(), 'UTF-8'))->toString();
                $page->setSlug($slug);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}