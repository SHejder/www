<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\Admin\ECommerce\Product;

use AppBundle\Entity\ECommerce\Product\AppPropertyValue;
use Darvin\ECommerceBundle\Entity\Product\PropertyInterface;
use Darvin\ECommerceBundle\Entity\Product\PropertyValue;
use Darvin\ECommerceBundle\Form\Type\Admin\Product\PropertyValueType;
use Darvin\Utils\Locale\LocaleProviderInterface;
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
class AppPropertyValuesType extends AbstractType
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
            /** @var \Darvin\ECommerceBundle\Entity\Product\PropertyValue[]|\Doctrine\Common\Collections\Collection $propertyValues */
            $propertyValues = $event->getData();
            $propertyIds = [];

            foreach ($propertyValues as $propertyValue) {
                $propertyIds[$propertyValue->getProperty()->getId()] = true;
            }

            /** @var \Darvin\ECommerceBundle\Entity\Product\Product $product */
            $product = $event->getForm()->getParent()->getData();

            /** @var \AppBundle\Entity\ECommerce\Product\AppProperty $property */
            foreach ($getProductPropertiesCallback($product->getCatalog()->getTreePath()) as $property) {
                if (!$property->isChoiceWithPrice()) {
                    if (isset($propertyIds[$property->getId()])) {
                        unset($propertyIds[$property->getId()]);

                        continue;
                    }

                    $propertyValue = new PropertyValue();
                    $propertyValue
                        ->setProduct($product)
                        ->setProperty($property);
                    $propertyValues->add($propertyValue);
                }
            }
            foreach ($propertyValues as $key => $propertyValue) {
                if (isset($propertyIds[$propertyValue->getProperty()->getId()])) {
                    $propertyValues->removeElement($propertyValue);

                    continue;
                }

                $child = $builder->create($key, PropertyValueType::class);
                $child->setData($propertyValue);
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
            'entry_type' => PropertyValueType::class,
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
        return $this->getProductPropertyRepository()->getByCatalogBuilder($productCatalogTreePath, $this->localeProvider->getCurrentLocale())
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
