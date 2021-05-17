<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un pseudo',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 32,
                        'minMessage' => 'Votre pseudo doit être d\'au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre pseudo doit être de moins de {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class )
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'block rounded-full w-full p-2 bg-grey focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent']],
                'first_options'  => ['label' => 'MOT DE PASSE'],
                'second_options' => ['label' => 'REPETEZ LE MOT DE PASSE'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 20,
                        'minMessage' => 'Votre mot de passe doit être d\'au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe doit être de moins de {{ limit }} caractères',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "allow_extra_fields" => true
        ]);
    }
}
