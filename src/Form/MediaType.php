<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Fichier image',
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier de type JPEG, PNG ou GIF.',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ]);

        $builder
            ->add('user', EntityType::class, [
                'label' => 'Utilisateur',
                'required' => false,
                'class' => User::class,
                'choices' => $options['users'],
                'choice_label' => 'username',
            ])
            ->add('album', EntityType::class, [
                'label' => 'Album',
                'required' => false,
                'class' => Album::class,
                'choices' => $options['albums'],
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'albums' => [],
            'users' => [],
        ]);
    }
}
