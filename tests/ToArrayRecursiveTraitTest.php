<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\ICanBoogie;

use ICanBoogie\ToArrayRecursive;
use ICanBoogie\ToArrayRecursiveTrait;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class ToArrayRecursiveTraitTest extends TestCase
{
	/**
	 * @dataProvider provide_instances
	 */
	public function test_to_array_recursive(A $instance, array $expected)
	{
		$this->assertEquals($expected, $instance->to_array_recursive());
	}

	public function provide_instances(): array
	{
		return [

			[
				new A([ 'a' => 'a', 'b' => 'b', 'c' => [ 'A' => 'A', 'B' => 'B' ] ]),

				[ 'a' => 'a', 'b' => 'b', 'c' => [ 'A' => 'A', 'B' => 'B' ]]

			],

			[
				new A([ 'a' => 'a', 'b' => 'b', 'c' => (object) [ 'A' => 'A', 'B' => 'B' ] ]),

				[ 'a' => 'a', 'b' => 'b', 'c' => (object) [ 'A' => 'A', 'B' => 'B' ]]

			],

			[
				new A([ 'a' => 'a', 'b' => 'b', 'c' => new A([ 'A' => 'A', 'B' => 'B' ]) ]),

				[ 'a' => 'a', 'b' => 'b', 'c' => [ 'A' => 'A', 'B' => 'B' ]]

			]

		];
	}
}

final class A implements ToArrayRecursive
{
	use ToArrayRecursiveTrait;

	public function __construct(array $properties)
	{
		foreach ($properties as $property => $value)
		{
			$this->$property = $value;
		}
	}

	public function to_array(): array
	{
		return (array) $this;
	}
}
