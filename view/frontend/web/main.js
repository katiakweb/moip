// 2017-04-11
define([
	// 2017-06-13 https://dev.moip.com.br/docs/criptografia#section--criptografia-no-browser-
	'df', 'df-lodash', 'Df_Checkout/data', 'Df_StripeClone/main', 'ko'
	,'Df_Ui/validator/cpf', '//assets.moip.com.br/v2/moip.min.js'
], function(df, _, dfc, parent, ko) {'use strict'; return parent.extend({
	defaults: {df: {card: {requireCardholder: true}}, taxID: ''},
	/**
	 * 2017-07-14
	 * @override
	 * @see Df_Payment/card::initialize()
	 * https://github.com/mage2pro/core/blob/2.4.21/Payment/view/frontend/web/card.js#L77-L110
	 * @returns {Object}
	*/
	initialize: function() {
		this._super();
		/** @type {Number[]} */
		var ia = this.config('installments');
		if (2 > ia.length) {
			ia = [];
		}
		this.installment = ko.observable(!ia.length ? 1 : ia[0]);
		var $t = this.$t;
		this.installments = _.map(ia, function(i) {return {
			label: df.t($t('{0}x {1}'), i, dfc.formatMoneyH(dfc.grandTotal() / i)) + (1 === i ? '' :
				' ' + $t('interest free')
			)
			,period: i
		}});
		return this;
	},
	/**
	 * 2017-06-13
	 * @override
	 * @see Df_Payment/card::dfCard_customTemplate_afterCardholder()
	 * https://github.com/mage2pro/core/blob/2.8.11/Payment/view/frontend/web/card.js#L56-L66
	 * @used-by https://github.com/mage2pro/core/blob/2.8.11/Payment/view/frontend/web/template/card/new.html#L56-L58
	 *	<!--ko if: dfCard_customTemplate_afterCardholder() -->
	 *		<!-- ko template: {name: dfCard_customTemplate_afterCardholder()} --><!-- /ko -->
	 *	<!--/ko-->
	 * @returns {String}
	 */
	dfCard_customTemplate_afterCardholder: function() {return 'Dfe_Moip/taxID';},
	/**
	 * 2017-07-14
	 * @override
	 * @see Df_Payment/card::dfCard_customTemplate_bottom()
	 * https://github.com/mage2pro/core/blob/2.8.11/Payment/view/frontend/web/card.js#L67-L68
	 * @used-by https://github.com/mage2pro/core/blob/2.8.11/Payment/view/frontend/web/template/card.html#L36-L38
	 *	<!--ko if: dfCard_customTemplate_bottom() -->
	 *		<!-- ko template: {name: dfCard_customTemplate_bottom()} --><!-- /ko -->
	 *	<!--/ko-->
	 * @returns {String}
	 */
	dfCard_customTemplate_bottom: function() {return 'Dfe_Moip/installments';},
	/**
	 * 2017-06-13 Задаёт набор передаваемых на сервер при нажатии кнопки «Place Order» данных.
	 * @override
	 * @see Df_Payment/card::dfData()
	 * @used-by Df_Payment/mixin::getData()
	 * https://github.com/mage2pro/core/blob/2.8.4/Payment/view/frontend/web/mixin.js#L224
	 * @returns {Object}
	 */
	dfData: function() {return df.o.merge(this._super(), {
		cardholder: this.cardholder(), taxID: this.taxID()
	});},
	/**
	 * 2017-04-11 The bank card network codes: https://mage2.pro/t/2647
	 * 2017-04-16 [Moip] The available payment options: https://mage2.pro/t/3851
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC', 'AE', 'DN', 'Hipercard', 'Hiper', 'Elo'];},
	/**
	 * 2017-07-12
	 * @override
	 * @see Df_Payment/card::initObservable()
	 * https://github.com/mage2pro/core/blob/2.8.4/Payment/view/frontend/web/card.js#L141-L157
	 * @used-by Magento_Ui/js/lib/core/element/element::initialize()
	 * https://github.com/magento/magento2/blob/2.2.0-RC1.3/app/code/Magento/Ui/view/base/web/js/lib/core/element/element.js#L104
	 * @returns {Element} Chainable
	*/
	initObservable: function() {this._super(); this.observe(['taxID']); return this;},
	/**
	 * 2017-07-12 «[Moip] What is CPF?» https://mage2.pro/t/3376
	 * @override
	 * @see Df_Payment/card::prefill()
	 * https://github.com/mage2pro/core/blob/2.8.3/Payment/view/frontend/web/card.js#L152-L167
	 * @used-by Df_Payment/card::initialize()
	 * https://github.com/mage2pro/core/blob/2.8.3/Payment/view/frontend/web/card.js#L134-L137
	 * @param {*} d
	 */
	prefill: function(d) {this._super(d); this.taxID('11438374798');},
    /**
	 * 2017-06-13
	 * @override
	 * @see Df_StripeClone/main::tokenCheckStatus()
	 * https://github.com/mage2pro/core/blob/2.7.9/StripeClone/view/frontend/web/main.js?ts=4#L8-L15
	 * @used-by Df_StripeClone/main::placeOrder()
	 * https://github.com/mage2pro/core/blob/2.7.9/StripeClone/view/frontend/web/main.js?ts=4#L75
	 * @param {Boolean} status
	 * @returns {Boolean}
	 */
	tokenCheckStatus: function(status) {return status;},
    /**
	 * 2017-06-13
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L21-L29
	 * @used-by Df_StripeClone/main::placeOrder()
	 * https://github.com/mage2pro/core/blob/2.7.9/StripeClone/view/frontend/web/main.js?ts=4#L73
	 * @param {Object} params
	 * @param {Function} callback
	 */
	tokenCreate: function(params, callback) {
		/** @type {Object} */
		var card = new Moip.CreditCard(params);
		callback(card.isValid(), card);
	},
    /**
	 * 2017-06-13
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L31-L39
	 * @used-by placeOrder()
	 * @param {Boolean} status
	 * @param {Object} resp
	 * @returns {String}
	 */
	tokenErrorMessage: function(status, resp) {return this.$t(
		'Unable to validate your bank card. Please recheck the data entered.'
	);},
    /**
	 * 2017-06-13
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L41-L48
	 * @used-by placeOrder()
	 * @param {Object} resp
	 * @returns {String}
	 */
	tokenFromResponse: function(resp) {return resp.hash();},
    /**
	 * 2017-06-13
	 * https://dev.moip.com.br/page/english#section-encryption-in-browser
	 * https://dev.moip.com.br/docs/criptografia#section--criptografia-no-browser-
	 * http://moip.github.io/moip-sdk-js
	 * @override
	 * @see Df_StripeClone/main::tokenParams()
	 * https://github.com/mage2pro/core/blob/2.7.9/StripeClone/view/frontend/web/main.js?ts=4#L42-L48
	 * @used-by Df_StripeClone/main::placeOrder()
	 * https://github.com/mage2pro/core/blob/2.7.9/StripeClone/view/frontend/web/main.js?ts=4#L73
	 * @returns {Object}
	 */
	tokenParams: function() {return {
		cvc: this.creditCardVerificationNumber()
		,expMonth: this.creditCardExpMonth()
		,expYear: this.creditCardExpYear()
		,number: this.creditCardNumber()
		,pubKey: this.publicKey()
	};}
});});