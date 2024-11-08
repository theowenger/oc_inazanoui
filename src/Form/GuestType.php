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

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [
            'label' => 'Nom',
        ]);
        $builder->add('description', TextType::class, [
            'label' => 'Description',
            'required' => false,
        ]);
        $builder->add('email', EmailType::class, [
            'label' => 'E-mail',
        ]);
        $builder->add('password', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'required' => false,
            'empty_data' => '',
            'attr' => [
                'placeholder' => 'Laissez vide pour ne pas changer le mot de passe',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
