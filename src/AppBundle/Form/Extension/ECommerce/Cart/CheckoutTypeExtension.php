<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Extension\ECommerce\Cart;

use AppBundle\Entity\ECommerce\Order\AppOrder;
use Darvin\ECommerceBundle\Form\Type\Cart\CheckoutType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cart checkout form type
 */
class CheckoutTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('userFullName');
        $builder->remove('userPhone');
        $builder->remove('userEmail');

        $builder
            ->add('userFullName', null, [
                'label' => 'ecommerce_order.entity.user_full_name',
                'attr'  => array('placeholder' => 'Ваше имя *'),
                'required' => false,
            ])
            ->add('userPhone', null, [
                'label' => 'Контактный телефон',
                'attr'  => array('placeholder' => 'Номер телефона *'),
            ])
            ->add('userInfo', null, [
                'label' => 'Дополнительная информация',
                'attr'  => array('placeholder' => 'Комментарии к заказу'),
            ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AppOrder::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CheckoutType::class;
    }
}
