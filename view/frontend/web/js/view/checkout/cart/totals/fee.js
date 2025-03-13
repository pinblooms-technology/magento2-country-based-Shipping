define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals'

], function (ko, Component, quote, priceUtils, totals) {
    'use strict';
    var show_hide_Extrafee_blockConfig = window.checkoutConfig.show_hide_Extrafee_block;
    var fee_label = window.checkoutConfig.fee_label;
    var custom_fee_amount = window.checkoutConfig.custom_fee_amount;
    var custom_in_fee_amount = window.checkoutConfig.custom_fee_amount_inc;

    return Component.extend({

        totals: quote.getTotals(),
        canVisibleExtrafeeBlock: show_hide_Extrafee_blockConfig,
        //getFormattedPrice: ko.observable(priceUtils.formatPrice(custom_fee_amount, quote.getPriceFormat())),
        //getFeeLabel: ko.observable(fee_label),
        getInFeeLabel: ko.observable(window.checkoutConfig.inclTaxPostfix),
        getExFeeLabel: ko.observable(window.checkoutConfig.exclTaxPostfix),

        isDisplayed: function () {
            console.log(this.getValue());
            return this.getValue() != 0;
        },
        isDisplayBoth: function () {
            console.log(window.checkoutConfig.displayBoth);

            return window.checkoutConfig.displayBoth;
        },
        displayExclTax: function () {
            console.log(window.checkoutConfig.displayExclTax);

            return window.checkoutConfig.displayExclTax;
        },
        displayInclTax: function () {
            console.log(window.checkoutConfig.displayInclTax);

            return window.checkoutConfig.displayInclTax;
        },
        isTaxEnabled: function () {
            console.log(window.checkoutConfig.TaxEnabled);

            return window.checkoutConfig.TaxEnabled;
        },
        getValue: function () {
            var price = 0;

            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee').value;
            }
            return price;
        },
        getFormattedPrice: function () {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee').value;
            }

            return priceUtils.formatPrice(price, quote.getPriceFormat());
        },

        getFeeLabel: function () {
            var title = "";
            if (this.totals() && totals.getSegment('fee')) {
                title = totals.getSegment('fee').title;
            }
            console.log(totals);
            return title;
        },
    });
});
