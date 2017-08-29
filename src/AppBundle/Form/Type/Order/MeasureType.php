<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13.07.2017
 * Time: 13:19
 */

namespace AppBundle\Form\Type\Order;

use AppBundle\Form\Type\PrivacyPolicyConfirmCheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MeasureType
 */
class MeasureType extends AbstractType
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
            ))
            ->add('policy', PrivacyPolicyConfirmCheckboxType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('label_format', 'measure_order.entity.%name%');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_measure_order';
    }

}