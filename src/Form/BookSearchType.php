<?php

namespace App\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder
        ->add('isbn', null, [
            'constraints' => [
                new Assert\Regex([
                    'pattern' => '/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/i',
                ])
            ]
        ])
        //this event listener add a checkboxtype field with the book name for the user to validate his choice
        ->get('isbn')
            ->addModelTransformer(new CallbackTransformer(
                function ($isbn) {
                    // transform the integer from db to a string
                    return $isbn;
                },
                function ($isbnFromView) {                  
                    // reverse transform => from view to db

                    $isbnWithoutDashes = str_replace("-","", $isbnFromView);
    
                    $isbnToint = intval($isbnWithoutDashes);

                    return $isbnToint;
                   
                }
            ))
            //this event listener add a checkboxtype field with the book name for the user to validate his choice
           ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($options) {
                $form = $event->getForm()->getParent(); // On récupère le formulaire
                $form->add('book', CheckboxType::class, [
                    'label' => $options['label'],
                    'required' => false,
                ]);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
