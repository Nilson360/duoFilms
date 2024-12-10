<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nome Completo',
                'attr' => ['class' => 'form-input', 'placeholder' => 'Digite o seu nome completo'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Endereço de E-mail',
                'attr' => ['class' => 'form-input', 'placeholder' => 'Digite o seu e-mail'],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Assunto',
                'attr' => ['class' => 'form-input', 'placeholder' => 'Digite o assunto'],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Mensagem',
                'attr' => ['class' => 'form-textarea', 'rows' => 5, 'placeholder' => 'Digite a sua mensagem'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
