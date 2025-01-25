<?php
declare(strict_types=1);

namespace BeckyDoggett\FreeShippingPromotion\Block\Cart;

use Magento\Framework\View\Element\Template;
use BeckyDoggett\FreeShippingPromotion\Model\Config\Config as ConfigProvider;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Locale\Format as LocaleFormat;

class ShippingPromotion extends Template
{

    /**
     * @var ConfigProvider
     */
    protected ConfigProvider $configProvider;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected CurrencyFactory $currencyFactory;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected Json $json;

    /**
     * @var \Magento\Framework\Locale\Format
     */
    protected LocaleFormat $localeFormat;

    /**
     * @param ConfigProvider $configProvider
     * @param CurrencyFactory $currencyFactory
     * @param Json $json
     * @param LocaleFormat $localeFormat
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ConfigProvider $configProvider,
        CurrencyFactory $currencyFactory,
        Json $json,
        LocaleFormat $localeFormat,
        Template\Context $context,
        array $data = []
    ) {
        $this->configProvider = $configProvider;
        $this->currencyFactory = $currencyFactory;
        $this->json = $json;
        $this->localeFormat = $localeFormat;
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

    /**
     * Convert minimum order amount using current store currency
     *
     * @return string | float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFreeShippingMinimumOrderAmountInStoreCurrency(): string|float
    {
        $minAmount = $this->getFreeShippingMinimumOrderAmount();
        $currentCurrency = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
        $baseCurrency = $this->_storeManager->getStore()->getBaseCurrency()->getCode();

        if ($currentCurrency != $baseCurrency) {
            $rate = $this->currencyFactory->create()->load($baseCurrency)->getAnyRate($currentCurrency);
            $minAmount = $minAmount * $rate;
        }

        return $minAmount;
    }

    /**
     * Format Price
     *
     * @return bool|string
     */
    public function getLocalePriceFormatJson(): bool|string
    {
        return $this->json->serialize($this->localeFormat->getPriceFormat());
    }
}
