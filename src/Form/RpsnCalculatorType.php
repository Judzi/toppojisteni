<?php

namespace App\Form;

use App\Entity\Calculator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RpsnCalculatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('houseValue', NumberType::class)
            ->add('amount', NumberType::class)
            ->add('repaymentTime', NumberType::class)
            ->add('fixationTime', ChoiceType::class, [
                'choices' => [
                    1 => 1,
                    3 => 3,
                    5 => 5,
                    10 => 10
                ]
            ])
            ->add('birthNumber', TextType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calculator::class
        ]);
    }
}
