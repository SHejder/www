<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\ECommerce\Cart;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use AppBundle\Entity\ECommerce\Cart\AdditionalPrice;
use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\AppProperty;
use AppBundle\Entity\ECommerce\Product\AppPropertyValue;
use AppBundle\Entity\ECommerce\Product\PropertyValuePrice;
use Darvin\ECommerceBundle\Entity\Product\Property;
use Darvin\ECommerceBundle\Entity\Product\PropertyValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Product property value admin form type
 */
class AdditionalPriceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            /** @var AdditionalPrice $additionalPrice */
            $additionalPrice = $event->getData();

            if (empty($additionalPrice)) {
                return;
            }

            if ($additionalPrice->getMain()) {
                $onChange = 'return changePropertyMain('.$additionalPrice->getProperty()->getId().',this);';
            } else {
                $onChange = 'return changeProperty('.$additionalPrice->getProperty()->getId().',this);';
            }

            $choices = $this->getPropertyValueWithPrice($additionalPrice->getProduct(), $additionalPrice->getProperty());

            if (!empty($choices)) {
                $event->getForm()
                    ->add('value_and_price', ChoiceType::class, [
                        'label' => $additionalPrice->getTitle(),
                        'required' => $additionalPrice->getMain(),
                        'choices' => $choices,
                        'expanded' => false,
                        'multiple' => false,
                        'placeholder' => ($additionalPrice->getMain() ? null : 'Выбрать'),
                        'attr' => array(
                            'onchange'  => $onChange,
                        ),
                    ]);

                $event->getForm()
                    ->add('count', TextType::class, [
                        'label' => 'Кол-во',
                        'required' => false,
                        'attr' => array(
                            'onchange' => 'return changePropertyCount(this);',
                            'class'     => ($additionalPrice->getMain()?'property_main':null),
                        ),
                    ]);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => AdditionalPrice::class,
            'label'           => false,
            'auto_initialize' => false,
            'required'        => false,
        ]);
    }

    /**
     * @param AppProduct  $product
     * @param AppProperty $property
     *
     * @return array
     */
    public function getPropertyValueWithPrice(AppProduct $product, AppProperty $property)
    {
        $result = array();
        /** @var $value AppPropertyValue */
        foreach ($property->getAppValues() as $value) {
            /** @var $propertyValuePrice PropertyValuePrice */
            foreach ($product->getPropertyValuePrices() as $propertyValuePrice) {
                if ($propertyValuePrice->getPropertyValue() === $value && $propertyValuePrice->getShowChoice()) {
                    $result[$value->getValue()] = $propertyValuePrice->getId().':'.$propertyValuePrice->getPrice().':'.base64_encode($value->getValue());
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'darvin_ecommerce_admin_product_property_value';
    }
}
