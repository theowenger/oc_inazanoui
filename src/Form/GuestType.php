<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template TData of User
 * @extends AbstractType<TData>
 */
class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new Assert\NotBlank(message: 'Le nom ne peut pas être vide.'),
                new Assert\Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ]);

        $builder->add('description', TextType::class, [
            'label' => 'Description',
            'required' => false,
            'constraints' => [
                new Assert\Length([
                    'max' => 255,
                    'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ]);

        $builder->add('email', EmailType::class, [
            'label' => 'E-mail',
            'constraints' => [
                new Assert\NotBlank(message: 'L\'e-mail ne peut pas être vide.'),
                new Assert\Email(message: 'Veuillez entrer un e-mail valide.'),
            ],
        ]);

        $builder->add('password', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'required' => false,
            'empty_data' => '',
            'attr' => [
                'placeholder' => 'Laissez vide pour ne pas changer le mot de passe',
            ],
//            'constraints' => [
//                new Assert\Length([
//                    'min' => 6,
//                    'max' => 255,
//                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
//                    'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
//                ]),
//            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}