<?php
namespace Dfe\Moip\API\Facade;
use Df\API\Operation as O;
use Df\Core\Exception as DFE;
/**
 * 2017-04-25 
 * https://dev.moip.com.br/page/api-reference#section-customers
 * https://dev.moip.com.br/v2.0/reference#clientes
 * create:
 * https://dev.moip.com.br/page/api-reference#section-create-a-customer-post-
 * https://dev.moip.com.br/v2.0/reference#criar-um-cliente
 * 2017-04-26 get:
 * https://dev.moip.com.br/page/api-reference#section-retrieve-a-customer-get-
 * https://dev.moip.com.br/v2.0/reference#consultar-um-cliente
 * $id should be a Moip internal customer identifier,
 * a value of the «id» property of a customer entity (like «CUS-UKXT2RQ2TULX»):
 *	{
 *		"id": "CUS-UKXT2RQ2TULX",
 *		"ownId": "admin@mage2.pro",
 *		"fullname": "Dmitry Fedyuk",
 *		"createdAt": "2017-04-25T04:38:31.000-03",
 *		<...>
 *	}
 * A value of the «ownId» property is not allowed here.
 * @method static Customer s()
 */
final class Customer extends \Df\API\Facade {
	/**
	 * 2017-06-10
	 * https://dev.moip.com.br/page/api-reference#section-add-a-credit-card-post-
	 * https://dev.moip.com.br/v2.0/reference#adicionar-cartao-de-credito
	 * [Moip] An example of a response to «POST v2/customers/<customer ID>/fundinginstruments»
	 * https://mage2.pro/t/4050
	 * @used-by \Dfe\Moip\Facade\Customer::create()
	 * @param string $customerId
	 * @param array(string => mixed) $a
	 * @return O
	 * @throws DFE
	 */
	function addCard($customerId, array $a) {return $this->p(
		$a, 'POST', "customers/$customerId/fundinginstruments"
	);}
}