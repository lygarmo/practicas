<?php

namespace App\Form;

use App\Entity\Libro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título del libro',
                'attr' => ['placeholder' => 'Introduce el título del libro'],
            ])
            ->add('autor', TextType::class, [
                'label' => 'Autor del libro',
                'attr' => ['placeholder' => 'Introduce el nombre del autor'],
            ])
            ->add('a_publicacion', IntegerType::class, [
                'label' => 'Año de publicación',
                'attr' => ['placeholder' => 'Introduce el año de publicación'],
            ])
            ->add('editorial', TextType::class, [
                'label' => 'Editorial',
                'attr' => ['placeholder' => 'Introduce la editorial'],
            ])
            ->add('guardar', SubmitType::class, [
                'label' => 'Guardar libro',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Libro::class,  // Vincula este formulario con la entidad Libro
        ]);
    }
}
