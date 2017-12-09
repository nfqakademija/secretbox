<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //        var_dump($options['parcelMachines']);die;
        $builder
        //            ->add('deliveryAddress', TextareaType::class, ['label' => 'order.delivery.address'])
            ->add(
                'parcelMachineDeliveryAddress',
                ChoiceType::class,
                [
                //                todo padaryt translationus
                'label' => 'pristatymas į pasirinktą paštomatą',
                'choices' => $options['parcelMachines'],

                ]
            )
            ->add('deliveryAddress', TextType::class, ['label' => 'kurjeris pristatys nurodytu adresu'])
            ->add(
                'save',
                SubmitType::class,
                [
                'label' => 'apmokėti',
                'attr' => ['class' => 'btn btn-lg btn-primary'],
                ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //        $resolver->setDefaults([
        //            'data_class' => Order::class,
        //        ]);
        $resolver
            ->setDefault('parcelMachines', null)
            ->setRequired('parcelMachines')
            ->setAllowedTypes('parcelMachines', array('array'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_order';
    }
}
