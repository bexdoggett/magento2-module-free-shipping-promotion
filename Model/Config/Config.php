<?php
declare(strict_types=1);

namespace BeckyDoggett\FreeShippingPromotion\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface as StoreScopeInterface;
use Magento\Store\Model\Store;

class Config
{

    private const FREE_SHIPPING_ACTIVE = 'carriers/freeshipping/active';

    private const FREE_SHIPPING_AMOUNT = 'carriers/freeshipping/free_shipping_subtotal';

    /**
     * @todo check tax setting
     * tax_including
     */

    /**
     * @todo check applicable countries
     * sallowspecific
     * specificcountry
     */

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if free shipping is active
     *
     * @param \Magento\Store\Model\Store|null $store
     * @return string|null
     */
    public function isFreeShippingActive(Store $store = null): ?string
    {
        return $this->scopeConfig->getValue(
            self::FREE_SHIPPING_ACTIVE,
            StoreScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get free shipping minimum order amount
     *
     * @param \Magento\Store\Model\Store|null $store
     * @return string|null
     */
    public function getFreeShippingMinimumOrderAmount(Store $store = null): ?string
    {
        return $this->scopeConfig->getValue(
            self::FREE_SHIPPING_AMOUNT,
            StoreScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
