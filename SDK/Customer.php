<?php
namespace Dfe\Moip\SDK;
use Moip\Exceptions\UnautorizedException as leUnautorized;
use Moip\Exceptions\UnexpectedException as leUnexpected;
use Moip\Exceptions\ValidationException as leValidation;
use Moip\Moip as API;
// 2017-04-25
final class Customer extends Entity {
	/**
	 * 2017-04-25 https://dev.moip.com.br/v2.0/reference#criar-um-cliente
	 * @used-by \Dfe\Moip\Facade\Customer::create()
	 * @param array(string => mixed) $a
	 * @return self
	 * @throws leUnautorized|leUnexpected|leValidation
	 */
	static function create(array $a) {return self::exec('POST', $a);}

	/**
	 * 2017-04-26 https://dev.moip.com.br/v2.0/reference#consultar-um-cliente
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
	 * @used-by \Dfe\Moip\Facade\Customer::create()
	 * @param string $id
	 * @return self
	 * @throws leUnautorized|leUnexpected|leValidation
	 */
	static function get($id) {return self::exec('GET', $id);}

	/**
	 * 2017-04-26
	 * @used-by create()
	 * @used-by get()
	 * @param string $verb
	 * @param string|array(string => mixed) $data
	 * @return self
	 */
	private static function exec($verb, $data) {
		/** @var Operation $op */
		$op = new Operation;
		/** @var string|null $id */
		list($id, $data) = is_array($data) ? [null, $data] : [$data, null];
		$op->exec("/v2/customers/$id", $verb, $data);
		return new self($op);
	}
}