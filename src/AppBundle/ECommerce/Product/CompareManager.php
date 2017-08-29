<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\ECommerce\Product;

use AppBundle\Entity\ECommerce\Product\AppProduct;
use Darvin\Utils\Locale\LocaleProviderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * E-commerce product compare manager
 */
class CompareManager
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
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $sessionKey;

    /**
     * @var bool
     */
    private $outdatedComparablesRemoved;

    /**
     * @param \Doctrine\ORM\EntityManager                                $em             Entity manager
     * @param \Darvin\Utils\Locale\LocaleProviderInterface               $localeProvider Locale provider
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session        Session
     * @param string                                                     $sessionKey     Session key
     */
    public function __construct(
        EntityManager $em,
        LocaleProviderInterface $localeProvider,
        SessionInterface $session,
        $sessionKey
    ) {
        $this->em = $em;
        $this->localeProvider = $localeProvider;
        $this->session = $session;
        $this->sessionKey = $sessionKey;

        $this->outdatedComparablesRemoved = false;
    }

    /**
     * @param \AppBundle\ECommerce\Product\Comparable $comparable Comparable
     *
     * @throws \AppBundle\ECommerce\Product\CompareManagerException
     */
    public function add(Comparable $comparable)
    {
        $comparables = $this->getComparables();

        if (isset($comparables[$comparable->getProductId()])) {
            throw new CompareManagerException(
                sprintf('Product with ID "%d" already added to compare.', $comparable->getProductId())
            );
        }

        $comparables[$comparable->getProductId()] = $comparable;

        $this->setComparables($comparables);
    }

    /**
     * @param int $productId Product ID
     *
     * @return bool
     */
    public function added($productId)
    {
        $comparables = $this->getComparables();

        return isset($comparables[$productId]);
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->removeOutdatedComparables();

        return count($this->getComparables());
    }

    /**
     * @param int $productId Product ID
     *
     * @throws \AppBundle\ECommerce\Product\CompareManagerException
     */
    public function delete($productId)
    {
        $comparables = $this->getComparables();

        if (!isset($comparables[$productId])) {
            throw new CompareManagerException(sprintf('Product with ID "%d" not added to compare.', $productId));
        }

        unset($comparables[$productId]);

        $this->setComparables($comparables);
    }

    /**
     * @return \AppBundle\ECommerce\Product\Comparable[]
     */
    public function getAddedComparables()
    {
        $this->removeOutdatedComparables();

        return $this->getComparables();
    }

    private function removeOutdatedComparables()
    {
        if ($this->outdatedComparablesRemoved) {
            return;
        }

        $comparables = $this->getComparables();

        if (empty($comparables)) {
            return;
        }

        $qb = $this->getProductRepository()->getByIdsEnabledBuilder(
            $this->localeProvider->getCurrentLocale(),
            array_keys($comparables),
            false
        );

        $productIds = array_map('intval', array_column($qb->select('o.id')->getQuery()->getScalarResult(), 'id'));
        $productIds = array_combine($productIds, $productIds);

        foreach ($comparables as $productId => $comparable) {
            if (!isset($productIds[$productId])) {
                unset($comparables[$productId]);
            }
        }

        $this->setComparables($comparables);

        $this->outdatedComparablesRemoved = true;
    }

    /**
     * @param \AppBundle\ECommerce\Product\Comparable[] $comparables Comparables
     */
    private function setComparables(array $comparables)
    {
        $this->session->set($this->sessionKey, $comparables);
    }

    /**
     * @return \AppBundle\ECommerce\Product\Comparable[]
     */
    private function getComparables()
    {
        return $this->session->get($this->sessionKey, []);
    }

    /**
     * @return \AppBundle\Repository\ECommerce\Product\AppProductRepository
     */
    private function getProductRepository()
    {
        return $this->em->getRepository(AppProduct::class);
    }
}
