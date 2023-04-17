<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordResetRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'constraints'=>[
                    new Assert\NotBlank(),
                ],
                'empty_data'=>'{{username}}',
                'row_attr'=>['class' =>'text-center'],
            ])
            ->add('submit',SubmitType::class,[
                'row_attr'=>['class'=>'my-3 text-center'],
                'label'=>'envoyez le lien'

            ])
            ->remove('mail')
            ->remove('password',)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
