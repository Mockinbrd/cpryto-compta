<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('purchaseDateType', DateType::class, [
                'mapped' => false,
                'required' => true,
                'input' => 'datetime_immutable',
                'widget' =>'single_text',
                'attr' => [
                    'class' => 'h-10 focus:outline-none focus:ring focus:border-blue-300 block w-full rounded-md sm:text-sm border-gray-300'
                ]
            ])
//            ->add('purchaseTimeType', TimeType::class, [
//                'mapped' => false,
//                'input' => 'array',
//                'widget' =>'single_text',
//                'attr' => [
//                    'class' => 'h-10 focus:outline-none focus:ring focus:border-blue-300 block w-1/2 rounded-md sm:text-sm border-gray-300'
//                ]
//            ])
            ->add('amountToCrypto')
            ->add('coinId',TextType::class, [
                'mapped' => false,
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
