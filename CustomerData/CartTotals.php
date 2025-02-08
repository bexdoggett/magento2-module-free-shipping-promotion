<?php
declare(strict_types=1);

namespace BeckyDoggett\FreeShippingPromotion\CustomerData;

use Magento\Checkout\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Psr\Log\LoggerInterface;

class CartTotals implements SectionSourceInterface
{

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected Session $checkoutSession;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected PricingHelper $pricingHelper;

    /**
     * @var \Magento\Quote\Api\CartTotalRepositoryInterface
     */
    protected CartTotalRepositoryInterface $cartTotalRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Construct
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Session $checkoutSession,
        PricingHelper $pricingHelper,
        CartTotalRepositoryInterface $cartTotalRepository,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->pricingHelper = $pricingHelper;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->logger = $logger;
    }

    /**
     * Add subtotal excl and incl data to customerData
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSectionData(): array
    {
        $quote = $this->checkoutSession->getQuote();

        if (!$quote || !$quote->getId()) {
            return [];
        }

        $additionalSectionData = [];

        try {
            $totals = $this->cartTotalRepository->get($quote->getId());
            //$this->logger->debug('Totals', $totals);

            if (isset($totals['subtotal'])) {
                $additionalSectionData = [
                    'subtotalExcTax' => $totals->getSubtotalExclTax() ?: $totals->getSubtotal(),
                    'subtotalIncTax' => $totals->getSubtotalInclTax(),
                ];
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e);
        }

        return $additionalSectionData;
    }
}
