<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nome Completo',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, informe seu nome completo',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Seu nome deve ter pelo menos {{ limit }} caracteres',
                        'maxMessage' => 'Seu nome não pode ter mais de {{ limit }} caracteres',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, informe seu email',
                    ]),
                    new Email([
                        'message' => 'O email {{ value }} não é um email válido',
                    ]),
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Telefone',
                'required' => false,
            ])
            ->add('subject', TextType::class, [
                'label' => 'Assunto',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, informe o assunto',
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Sua Mensagem',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, escreva sua mensagem',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Sua mensagem deve ter pelo menos {{ limit }} caracteres',
                    ]),
                ],
            ])
            ->add('privacy', CheckboxType::class, [
                'label' => false,
                'mapped' => false, // Não está mapeado para a entidade
                'constraints' => [
                    new IsTrue([
                        'message' => 'Você deve concordar com nossa política de privacidade',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}