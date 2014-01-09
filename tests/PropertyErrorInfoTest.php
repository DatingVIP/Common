<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie;

use ICanBoogie\PropertyErrorInfoTest\Container;

class PropertyErrorInfoTest extends \PHPUnit_Framework_TestCase
{
	static private $container;
	static private $fixture;

	static public function setupBeforeClass()
	{
		self::$fixture = new PropertyErrorInfo('property', self::$container, 'message');
	}

	/**
	 * @dataProvider provide_properties
	 */
	public function test_get($property, $value)
	{
		$this->assertSame($value, self::$fixture->$property);
	}

	/**
	 * @expectedException ICanBoogie\PropertyNotDefined
	 */
	public function test_get_undefined()
	{
		self::$fixture->__undefined__;
	}

	public function provide_properties()
	{
		# we initialize `container` here because the provider is called before the test is setup
		self::$container = new Container();

		return array
		(
			array('property', 'property'),
			array('container', self::$container),
			array('message', 'message')
		);
	}
}

namespace ICanBoogie\PropertyErrorInfoTest;

class Container
{

}