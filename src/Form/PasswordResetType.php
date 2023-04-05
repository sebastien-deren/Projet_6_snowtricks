<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plain_password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'options'=>['attr'=>['class'=>'mb-5']],
                'first_options'=>['label'=>'password'],
                'second_options'=>['label'=>'repeat password'],
                'constraints' => [
                    new Assert\NotCompromisedPassword(),
                    new Assert\NotBlank(),
                ],
                'mapped'=>false,
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'changer de Mot de Passe',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
