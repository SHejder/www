<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13.07.2017
 * Time: 13:19
 */

namespace AppBundle\Form\Type\Order;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CallType
 */
class CallType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Ваше имя',
                ),
            ))
            ->add('phone', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Номер телефона',
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('label_format', 'call_order.entity.%name%');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_call_order';
    }

}