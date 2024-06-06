/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'uiComponent',
    'jquery/mask'
], function ($, Component) {
    'use strict';

    return Component.extend({
        passwordSelector: '',
        passwordInputType: 'password',
        textInputType: 'text',

        defaults: {
            template: 'Magento_Customer/show-password',
            isPasswordVisible: false
        },

        /**
         * @return {Object}
         */
        initObservable: function () {
            this._super()
                .observe(['isPasswordVisible']);

            this.isPasswordVisible.subscribe(function (isChecked) {
                this._showPassword(isChecked);
            }.bind(this));

            this.maskTaxvatCustomer();

            return this;
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
        },

        /**
         * Show/Hide password
         * @private
         */
        _showPassword: function (isChecked) {
            $(this.passwordSelector).attr('type',
                isChecked ? this.textInputType : this.passwordInputType
            );
        }
    });
});
