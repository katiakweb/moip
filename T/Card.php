<?php
namespace Dfe\Moip\T;
use Dfe\Moip\SDK\Customer as C;
use Geocoder\Model\Address as GA;
use Moip\Exceptions\UnautorizedException as leUnautorized;
use Moip\Exceptions\UnexpectedException as leUnexpected;
use Moip\Exceptions\ValidationException as leValidation;
use Moip\Moip as API;
// 2017-04-26
final class Card extends TestCase {
	/** @test 2017-04-26 */
	function t00() {}

	/** 2017-04-26 */
	function t01_add() {
		try {
			echo 'OK';
		}
		catch (\Exception $e) {
			/** @var \Exception|leUnautorized|leUnexpected|leValidation $e */
			xdebug_break();
			throw $e;
		}
	}
}