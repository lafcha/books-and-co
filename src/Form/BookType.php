<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('cover')
            ->add('editor')
            ->add('year', null, [
                'attr' => [
                    'max' => date('Y'),
                    'maxMessage' => 'Année incorrecte'
                ]
            ])
            ->add('description')
            ->add('isbn', null, [
                'constraints' => new Assert\Regex([
                    'pattern' => '/^\d{13}$/i',
                    'message' => 'L\'isbn doit être de 13 chiffres',
                ])
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'modifier',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
