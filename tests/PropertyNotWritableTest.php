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

use ICanBoogie\PropertyNotWritableTest\Container;

class PropertyNotWritableTest extends \PHPUnit_Framework_TestCase
{
	public function test_with_message()
	{
		$e = new PropertyNotWritable('message');
		$this->assertEquals('message', $e->getMessage());
		$this->assertInstanceOf('ICanBoogie\PropertyErrorInfo', $e->error_info);
		$this->assertNull($e->property);
		$this->assertNull($e->container);
	}

	public function test_with_property()
	{
		$e = new PropertyNotWritable(array('property'));
		$this->assertEquals('The property <q>property</q> is not writable.', $e->getMessage());
		$this->assertInstanceOf('ICanBoogie\PropertyErrorInfo', $e->error_info);
		$this->assertSame('property', $e->property);
		$this->assertNull($e->container);
	}

	public function test_with_property_and_container()
	{
		$c = new Container;
		$e = new PropertyNotWritable(array('property', $c));
		$this->assertEquals('The property <q>property</q> for object of class <q>ICanBoogie\PropertyNotWritableTest\Container</q> is not writable.', $e->getMessage());
		$this->assertInstanceOf('ICanBoogie\PropertyErrorInfo', $e->error_info);
		$this->assertSame('property', $e->property);
		$this->assertSame($c, $e->container);
	}

	public function test_with_error_info()
	{
		$c = new Container;
		$error_info = new PropertyErrorInfo('property', $c, 'message');
		$e = new PropertyNotWritable($error_info);
		$this->assertEquals('message', $e->getMessage());
		$this->assertInstanceOf('ICanBoogie\PropertyErrorInfo', $e->error_info);
		$this->assertSame($error_info, $e->error_info);
		$this->assertSame('property', $e->property);
		$this->assertSame($c, $e->container);
	}
}

namespace ICanBoogie\PropertyNotWritableTest;

class Container
{

}