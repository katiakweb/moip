<?php
namespace Dfe\Moip;
use Dfe\Moip\SDK\Option;
// 2017-06-11
final class Charge extends \Df\StripeClone\Charge {
	/**
	 * 2017-06-11
	 * @override
	 * @see \Df\StripeClone\Charge::cardIdPrefix()
	 * @used-by \Df\StripeClone\Charge::usePreviousCard()
	 * @return string
	 */
	protected function cardIdPrefix() {return null;}

	/**
	 * 2017-06-11
	 * @override
	 * @see \Df\StripeClone\Charge::pCharge()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {return [];}
	
	/**
	 * 2017-06-11
	 * @override
	 * @see \Df\StripeClone\Charge::pCustomer()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @return array(string => mixed)
	 */
	protected function pCustomer() {return [];}

	/**
	 * 2017-06-11
	 * Ключ, значением которого является токен банковской карты.
	 * Этот ключ передаётся как параметр ДВУХ РАЗНЫХ запросов к API ПС:
	 * 1) в запросе на проведение транзакции (charge)
	 * 2) в запросе на сохранение банковской карты для будущего повторного использования
	 * @override
	 * @see \Df\StripeClone\Charge::k_CardId()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return string
	 */
	protected function k_CardId() {return null;}

	/**
	 * 2017-06-11
	 * https://github.com/mage2pro/moip/blob/0.4.2/T/CaseT/Payment.php#L50-L53
	 * @override
	 * @see \Df\StripeClone\Charge::k_DSD()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return string
	 */
	protected function k_DSD() {return 'statementDescriptor';}

	/**
	 * 2017-06-11
	 * @override
	 * @see \Df\StripeClone\Charge::v_CardId()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @param string $id
	 * @param bool $isPrevious [optional]
	 * @return array(string => mixed)
	 */
	protected function v_CardId($id, $isPrevious = false) {return [
		// 2017-06-09
		// «Credit Card data. It can be:
		// *) the ID of a credit card previously saved,
		// *) an encrypted credit card hash
		// *) the whole collection of credit card attributes (in case you have PCI DSS certificate).»
		// [Moip] The test bank cards: https://mage2.pro/t/3776
		//
		// hash: «Encrypted credit card data»
		// Conditional, String.
		// https://dev.moip.com.br/v2.0/docs/criptografia
		//
		// id: «Credit card ID.
		// This ID can be used in the future to create new payments. Internal reference.»
		// Conditional, String(16).
		'creditCard' => [$isPrevious ? 'id' : 'hash' => $id]
		// 2017-06-09
		// «Method used. Possible values: CREDIT_CARD, BOLETO, ONLINE_BANK_DEBIT, WALLET»
		// Required, String.
		,'method' => Option::BANK_CARD
	];}
}