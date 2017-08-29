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

use AppBundle\Entity\ECommerce\Product\AppPropertyValue;
use AppBundle\Entity\ECommerce\Product\PropertyValuePrice;
use Darvin\ECommerceBundle\Entity\Product\PropertyInterface;
use Darvin\ECommerceBundle\Entity\Product\PropertyValue;
use Darvin\ECommerceBundle\Form\Type\Admin\Product\PropertyValueType;
use Darvin\Utils\Locale\LocaleProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Product property values admin form type
 */
class AppPropertyValuePricesType extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Darvin\Utils\Locale\LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @param \Doctrine\ORM\EntityManager                  $em             Entity manager
     * @param \Darvin\Utils\Locale\LocaleProviderInterface $localeProvider Locale provider
     */
    public function __construct(EntityManager $em, LocaleProviderInterface $localeProvider)
    {
        $this->em = $em;
        $this->localeProvider = $localeProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var callable $getProductPropertiesCallback */
        $getProductPropertiesCallback = [$this, 'getProductProperties'];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $getProductPropertiesCallback) {
            /** @var \AppBundle\Entity\ECommerce\Product\PropertyValuePrice[]|\Doctrine\Common\Collections\Collection $propertyValuePrices */
            $propertyValuePrices = $event->getData();
            $propertyValueIds = [];

            /** @var $propertyValuePrice PropertyValuePrice */
            foreach ($propertyValuePrices as $propertyValuePrice) {
                $propertyValueIds[$propertyValuePrice->getPropertyValue()->getId()] = true;
            }

            /** @var \Darvin\ECommerceBundle\Entity\Product\Product $product */
            $product = $event->getForm()->getParent()->getData();

            /** @var \AppBundle\Entity\ECommerce\Product\AppProperty $property */
            foreach ($getProductPropertiesCallback($product->getCatalog()->getTreePath()) as $property) {
                if ($property->isChoiceWithPrice()) {
                    /** @var $value AppPropertyValue */
                    foreach ($property->getAppValues() as $value) {
                        if (isset($propertyValueIds[$value->getId()])) {
                            unset($propertyValueIds[$value->getId()]);

                            continue;
                        }

                        $propertyValuePrice = new PropertyValuePrice();
                        $propertyValuePrice
                            ->setProduct($product)
                            ->setPropertyValue($value);

                        $propertyValuePrices->add($propertyValuePrice);
                    }
                }
            }

//            $iterator = $propertyValuePrices->getIterator();
//            $iterator->uasort(function ($a, $b) {
//                $aPos = $a->getPropertyValue()->getProperty()->getPosition().$a->getPropertyValue()->getPosition();
//                $bPos = $b->getPropertyValue()->getProperty()->getPosition().$b->getPropertyValue()->getPosition();
//                return ($aPos < $bPos) ? -1 : 1;
//            });
//            $propertyValuePrices = new ArrayCollection(iterator_to_array($iterator));

            foreach ($propertyValuePrices as $key => $propertyValuePrice) {
                if (isset($propertyIds[$propertyValuePrice->getPropertyValue()->getId()])) {
                    $propertyValuePrices->removeElement($propertyValuePrice);

                    continue;
                }

                $child = $builder->create($key, PropertyValuePriceType::class);
                $child->setData($propertyValuePrice);
                $event->getForm()->add($child->getForm());
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => PropertyValuePriceType::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * @param string $productCatalogTreePath Product catalog tree path
     *
     * @return \Darvin\ECommerceBundle\Entity\Product\Property[]
     */
    protected function getProductProperties($productCatalogTreePath)
    {
        return $this->getProductPropertyRepository()
            ->getByCatalogBuilder($productCatalogTreePath, $this->localeProvider->getCurrentLocale())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return \Darvin\ECommerceBundle\Repository\Product\PropertyRepository
     */
    private function getProductPropertyRepository()
    {
        return $this->em->getRepository(PropertyInterface::class);
    }
}
