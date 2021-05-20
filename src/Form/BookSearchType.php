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
        ->add('isbn')
        //this event listener add a checkboxtype field with the book name for the user to validate his choice
        ->get('isbn')
            ->addModelTransformer(new CallbackTransformer(
                function ($isbn) {
                    // transform the array to a string
                    return $isbn;
                },
                function ($isbnFromView) {
                    // reverse transform view to db
                    $isbnFromView->trim('-');
                    $isbnToint = intval($isbnFromView);

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
