define([
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/price-utils',
    'jquery',
    'mage/mage'
], function (
    ko,
    Component,
    customerData,
    priceUtils,
    $,
    mage
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'BeckyDoggett_FreeShippingPromotion/free-shipping-promotion',
            minimumOrderAmount: 0,
            priceLocaleFormat: {
                decimalSymbol: '.',
                groupLength: 3,
                groupSymbol: ',',
                integerRequired: false,
                pattern: "£%s",
                precision: 2,
                requiredPrecision: 2
            },
            freeMinimumAmountMessage: 'Free shipping for orders over %1.',
            spendMoreMessage: 'Spend another %1 for free shipping.'
        },

        eligible: ko.observable(false),

        initialize: function() {
            this._super();
            this.cart = customerData.get('cart');
        },

        // Get cart items count
        getCartItemsTotal: function() {
            return customerData.get('cart')().summary_count;
        },

        // Get cart subtotal
        getCartSubtotal: function() {
            return customerData.get('cart')().subtotalAmount;
        },

        // Check if current cart is eligible for Free Shipping
        hasFreeShipping: function() {
            //If cart subtotal is greater or equal to minimum order amount
            if(parseFloat(this.getCartSubtotal()) >= parseFloat(this.minimumOrderAmount)) {
                this.eligible(true);
            }
            else {
                this.eligible(false);
            }

            return this.eligible;
        },

        // Format minimum order amount
        formatMinimumOrderAmount: function() {
            return priceUtils.formatPriceLocale(this.minimumOrderAmount, this.priceLocaleFormat);
        },

        // Get free shipping avaialble when you spend x info message
        getFreeShippingMessage: function() {
            const minimumOrderFormatted = this.formatMinimumOrderAmount();
            return $.mage.__(this.freeMinimumAmountMessage).replace('%1', minimumOrderFormatted);
        },

        // Calculate how much left to spend and format
        spendXForFreeShipping: function() {
            let spend = 0;
            // Check if already eligible for free shipping already
            if( !this.eligible() ){
                spend = this.minimumOrderAmount - this.getCartSubtotal();
            }
            return priceUtils.formatPriceLocale(spend, this.priceLocaleFormat);
        },

        // Get how much left to spend message
        getSpendXForFreeShippingMessage: function() {
            const spendXAmount = this.spendXForFreeShipping();
            return $.mage.__(this.spendMoreMessage).replace('%1', spendXAmount);
        },

        // Get amount spent as a percentage of the minimum order amount for a progress bar
        getProgressPercent: function() {
            let percent = 100;
            if( !this.eligible() ){
                percent = ((this.getCartSubtotal() * 100) / this.minimumOrderAmount);
            }
            return percent;
        }
    })
});
