define(['jquery',
        'uiComponent',
        'ko',
        'Magento_Customer/js/customer-data'
    ],
    function ($, Component, ko, customerData) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'AHT_RequestSample/knockout'
            },
            initialize: function () {
                this.customer_name = customerData.get('customer')().fullname;
                this.email_customer = customerData.get('customer')().email;
                this.city_customer = customerData.get('customer')().city_customer;
                this.country_customer = customerData.get('customer')().country_customer;
                this.address_customer = customerData.get('customer')().address_customer;
                this.addressline_customer = customerData.get('customer')().addressline_customer;
                this.zipcode_customer = customerData.get('customer')().zipcode_customer;
                this.numberphone_customer = customerData.get('customer')().numberphone_customer;
                this.country_all = customerData.get('customer')().country_all;
                this.state_all = customerData.get('customer')().state_all;
                this.state_customer = customerData.get('customer')().state_customer;
                this._super();
            },
        });
    }
);
