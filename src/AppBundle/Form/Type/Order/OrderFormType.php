<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 12.09.2017
 * Time: 16:20
 */

namespace AppBundle\Form\Type\Order;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Darvin\ECommerceBundle\Entity\Product;


/**
 * Class OrderFormType
 */

class OrderFormType extends AbstractType
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
                    'placeholder' => 'Контактный телефон',
                ),
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Адрес электронной почты',
                ),
            ))
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Введите сообщение.',
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('label_format', 'question_form_order.entity.%name%');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_form_order';
    }
}

