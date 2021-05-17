<?php

namespace App\Form;

use App\Entity\UsersBook;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Faire une demande de prêt pour ce livre',
                'attr' => ['class' => 'bg-pink text-white text-center font-semibold px-4 py-2 rounded-full']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UsersBook::class,
        ]);
    }
}
