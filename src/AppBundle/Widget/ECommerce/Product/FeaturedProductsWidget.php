<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Widget\ECommerce\Product;

use AppBundle\Configuration\AppECommerceConfiguration;
use AppBundle\Entity\ECommerce\Product\AppProduct;
use Darvin\AdminBundle\CKEditor\AbstractCKEditorWidget;
use Darvin\Utils\Service\ServiceProviderInterface;
use Doctrine\ORM\EntityManager;

/**
 * Featured e-commerce products widget
 */
class FeaturedProductsWidget extends AbstractCKEditorWidget
{
    /**
     * @var \AppBundle\Configuration\AppECommerceConfiguration
     */
    private $ecommerceConfig;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Darvin\Utils\Service\ServiceProviderInterface
     */
    private $templatingProvider;

    /**
     * @param \AppBundle\Configuration\AppECommerceConfiguration $ecommerceConfig    E-commerce configuration
     * @param \Doctrine\ORM\EntityManager                        $em                 Entity manager
     * @param \Darvin\Utils\Service\ServiceProviderInterface     $templatingProvider Templating service provider
     */
    public function __construct(
        AppECommerceConfiguration $ecommerceConfig,
        EntityManager $em,
        ServiceProviderInterface $templatingProvider
    ) {
        $this->ecommerceConfig = $ecommerceConfig;
        $this->em = $em;
        $this->templatingProvider = $templatingProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->getTemplating()->render(':ecommerce/product/widget:featured_products.html.twig', [
            'products' => $this->getProducts(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'featured_products';
    }

    /**
     * @return \AppBundle\Entity\ECommerce\Product\AppProduct[]
     */
    private function getProducts()
    {
        $products = $this->getProductRepository()->getFeaturedBuilder($this->ecommerceConfig->getFeaturedProducts())
            ->getQuery()
            ->getResult();

        shuffle($products);

        return $products;
    }

    /**
     * @return \AppBundle\Repository\ECommerce\Product\AppProductRepository
     */
    private function getProductRepository()
    {
        return $this->em->getRepository(AppProduct::class);
    }

    /**
     * @return \Symfony\Component\Templating\EngineInterface
     */
    private function getTemplating()
    {
        return $this->templatingProvider->getService();
    }
}
