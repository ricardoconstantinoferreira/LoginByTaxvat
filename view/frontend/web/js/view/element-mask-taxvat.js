define([
    'uiComponent',
    'jquery',
    'jquery/mask'
], function (
    Component,
    $
) {
    'use strict';

    return Component.extend({

        initialize: function () {
            this.maskTaxvatCustomer();
        },

        maskTaxvatCustomer: function() {

            $('input#email').keyup(function(e) {
                let username = $(this).val();
                let number = username.charAt(0);

                if (!isNaN(number) && number != "") {
                    $(this).mask('000.000.000-00');
                } else {
                    $(this).unmask();
                }
            });
        }
    });
});
