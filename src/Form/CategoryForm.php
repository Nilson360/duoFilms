<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryForm extends AbstractType
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome da Categoria',
                'constraints' => [
                    new NotBlank(['message' => 'O nome da categoria não pode estar vazio']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'O nome deve ter pelo menos {{ limit }} caracteres',
                        'maxMessage' => 'O nome deve ter no máximo {{ limit }} caracteres',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Ex: Casamentos, Eventos, Retratos...'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: casamentos (deixe em branco para gerar automaticamente)'
                ],
                'help' => 'Utilizado na URL. Deixe em branco para gerar automaticamente a partir do nome.'
            ])
            ->add('photos', EntityType::class, [
                'class' => Photo::class,
                'choice_label' => 'titlte', // Supondo que sua entidade Photo tenha um método getTitlte() (que parece ter um erro de ortografia)
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Fotos',
                'attr' => [
                    'class' => 'select2'
                ],
                'help' => 'Selecione as fotos para esta categoria'
            ])
        ;

        // Evento para gerar slug automaticamente
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $category = $event->getData();

            // Se o slug não foi preenchido, gera-o a partir do nome
            if (empty($category->getSlug()) && $category->getName()) {
                $slug = $this->slugger->slug(mb_strtolower($category->getName(), 'UTF-8'))->toString();
                $category->setSlug($slug);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}