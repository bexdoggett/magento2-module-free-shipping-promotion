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

        getCartItemsTotal: function() {
            return customerData.get('cart')().summary_count;
        },

        getCartSubtotal: function() {
            return customerData.get('cart')().subtotalAmount;
        },

        hasFreeShipping: function() {
            //If cart subtotal is greater or equal to minimum order amount
            console.log('totals', [this.getCartSubtotal(),this.minimumOrderAmount]);
            if(parseFloat(this.getCartSubtotal()) >= parseFloat(this.minimumOrderAmount)) {
                this.eligible(true);
                console.log('yes');
            }
            else {
                this.eligible(false);
                console.log('no');
            }
            console.log('Free?', this.eligible);

            return this.eligible;
        },

        formatMinimumOrderAmount: function() {
            return priceUtils.formatPriceLocale(this.minimumOrderAmount, this.priceLocaleFormat);
        },

        getFreeShippingMessage: function() {
            const minimumOrderFormatted = this.formatMinimumOrderAmount();
            return $.mage.__(this.freeMinimumAmountMessage).replace('%1', minimumOrderFormatted);
        },

        spendXForFreeShipping: function() {
            let spend = 0;
            // Check if already eligible for free shipping already
            if( !this.eligible() ){
                spend = this.minimumOrderAmount - this.getCartSubtotal();
            }
            return priceUtils.formatPriceLocale(spend, this.priceLocaleFormat);
        },

        getSpendXForFreeShippingMessage: function() {
            const spendXAmount = this.spendXForFreeShipping();
            return $.mage.__(this.spendMoreMessage).replace('%1', spendXAmount);
        }
    })
});
