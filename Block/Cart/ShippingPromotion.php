<?php
declare(strict_types=1);

namespace BeckyDoggett\FreeShippingPromotion\Block\Cart;

use Magento\Framework\View\Element\Template;
use BeckyDoggett\FreeShippingPromotion\Model\Config\Config as ConfigProvider;

class ShippingPromotion extends Template
{

    /**
     * @var ConfigProvider
     */
    protected ConfigProvider $configProvider;

    /**
     * Construct
     *
     * @param ConfigProvider $configProvider
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ConfigProvider $configProvider,
        Template\Context $context,
        array $data = []
    ) {
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
    }

    /**
     * Check if free shipping is active
     *
     * @return bool
     */
    public function isFreeShippingActive(): bool
    {
        return filter_var($this->configProvider->isFreeShippingActive(), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get free shipping minimum order amount
     *
     * @return string|null
     */
    public function getFreeShippingMinimumOrderAmount(): ?string
    {
        return $this->configProvider->getFreeShippingMinimumOrderAmount();
    }
}
