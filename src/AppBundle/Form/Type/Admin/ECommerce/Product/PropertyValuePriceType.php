<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\Admin\ECommerce\Product;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use AppBundle\Entity\ECommerce\Product\PropertyValuePrice;
use Darvin\ECommerceBundle\Entity\Product\Property;
use Darvin\ECommerceBundle\Entity\Product\PropertyValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Product property value admin form type
 */
class PropertyValuePriceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            /** @var \AppBundle\Entity\ECommerce\Product\PropertyValuePrice $propertyValuePrice */
            $propertyValuePrice = $event->getData();

            if (empty($propertyValuePrice)) {
                return;
            }

            $propertyValue = $propertyValuePrice->getPropertyValue();

            $event->getForm()->add('show_choice', CheckboxType::class, [
                'label' => $propertyValue->getProperty()->getTitle().' - '.$propertyValue->getValue(),
            ]);

            $event->getForm()->add('price', TextType::class, [
                'label' => 'Прибавка к цене',
                'attr' => array('scale' => 2, 'class' => 'number'),
                'required' => true,
            ]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => PropertyValuePrice::class,
            'label'           => false,
            'auto_initialize' => false,
            'required'        => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'darvin_ecommerce_admin_product_property_value';
    }
}
