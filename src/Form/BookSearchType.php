<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isbn', null, [
                'constraints' => new Assert\Regex([
                    'pattern' => '/^\d{13}$/i',
                    'message' => 'L\'isbn doit être de 13 chiffres',
                ])
            ])
            //this event listener add a checkboxtype field with the book name for the user to validate his choice
            ->get('isbn')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($options) {
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
