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

use AppBundle\Entity\ECommerce\Cart\AdditionalPrice;
use AppBundle\Entity\ECommerce\Cart\AppItem;
use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\AppProperty;
use AppBundle\Entity\ECommerce\Product\AppPropertyValue;
use AppBundle\Entity\ECommerce\Product\PropertyValuePrice;
use AppBundle\Entity\ECommerce\Product\Variant;
use AppBundle\Form\Type\ECommerce\Cart\AdditionalPriceType;
use Darvin\ECommerceBundle\Form\Type\Cart\ItemType;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cart item form type
 */
class ItemTypeExtension extends AbstractTypeExtension
{
    /** @var EntityManager $em Entity Manager */
    protected $em;

    /**
     * ItemTypeExtension constructor.
     * @param EntityManager $em Entity Manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {

            /** @var $appItem AppItem */
            $appItem = $builder->getData();

            $event->getForm()
                ->add('variant', EntityType::class, [
                    'class' => Variant::class,
                    'label' => 'Варианты',
                    'required' => true,
                    'query_builder' => $this->em->getRepository(Variant::class)->getListForProductBuilder($appItem->getProduct()),
                    'expanded' => true,
                    'multiple' => false,
//                    'choice_label' => 'id',
                ]);

            $properties = $this->em->getRepository(AppProperty::class)->getListForProductBuilder($appItem->getProduct())->getQuery()->getResult();

            $isMain = false;
            /** @var $property AppProperty */
            foreach ($properties as $property) {
                $additionalPrice = new AdditionalPrice();
                $additionalPrice->setTitle($property->getTitle());
                $additionalPrice->setProduct($appItem->getProduct());
                $additionalPrice->setProperty($property);
                if (!$isMain && $property->getMain()) {
                    $additionalPrice->setMain(true);
                    $isMain = true;
                    $additionalPrice->setCount(1);
                }
                $appItem->getAdditionalPrices()->set($property->getId(), $additionalPrice);
            }

            foreach ($appItem->getAdditionalPrices() as $key => $additionalPrice) {
                $child = $builder->create($additionalPrice->getProperty()->getId(), AdditionalPriceType::class);
                $child->setData($additionalPrice);
                $event->getForm()->add($child->getForm());
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', AppItem::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ItemType::class;
    }
}
