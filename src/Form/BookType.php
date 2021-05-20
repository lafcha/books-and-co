<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Merci de remplir le titre !'
                    ]),
                ]
            ])
            ->add('author',null, [
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Merci de remplir l\'auteur !'
                    ]),
                ]
            ])
            ->add('coverFile', FileType::class, [
                'label' => 'Couverture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ]
                    ])
                ],
            ])
            ->add('editor', null, [
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Merci de remplir l\'éditeur !'
                    ]),
                ]
            ])
            ->add('year', null, [
                'attr' => [
                    'max' => date('Y'),
                    'maxMessage' => 'Année incorrecte'
                ]
            ])
            ->add('description')
            ->add('isbn', null, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{13}$/i',
                        'message' => 'L\'ISBN doit être de 13 chiffres',
                    ]),
                    new NotBlank([
                        'message' =>'Merci de rentrer un ISBN'
                    ]) 
                ]
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
