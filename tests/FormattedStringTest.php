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

use ICanBoogie\FormattedString;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class FormattedStringTest extends TestCase
{
	public function testNoArgs()
	{
		$s = new FormattedString('Testing... :a :b');
		$this->assertEquals('Testing... :a :b', (string) $s);
	}

	public function testArgsArray()
	{
		$s = new FormattedString('Testing... :a :b', [ 'a' => 'A', 'b' => 'B' ]);
		$this->assertEquals('Testing... A B', (string) $s);
	}

	public function testArgArrayAsIndex()
	{
		$s = new FormattedString('Testing... {0} {1}', [ 'a' => 'A', 'b' => 'B' ]);
		$this->assertEquals('Testing... A B', (string) $s);
	}

	public function testArgArrayIndex()
	{
		$s = new FormattedString('Testing... {0} {1}', [ 'A', 'B' ]);
		$this->assertEquals('Testing... A B', (string) $s);
	}

	public function testArgList()
	{
		$s = new FormattedString('Testing... {0} {1}', 'A', 'B');
		$this->assertEquals('Testing... A B', (string) $s);
	}

	public function testEscaping()
	{
		$s = new FormattedString('Testing... !a', [ 'a' => '<>' ]);
		$this->assertEquals('Testing... &lt;&gt;', (string) $s);
	}

	/**
	 * The string shall not be formatted because we explicitely requested escaping.
	 */
	public function testExplicitEscaping()
	{
		$s = new FormattedString('Testing... :a', [ '!a' => '<>' ]);
		$this->assertEquals('Testing... :a', (string) $s);
	}

	public function testQuoting()
	{
		$s = new FormattedString('Testing... %a', [ 'a' => 'A' ]);
		$this->assertEquals('Testing... `A`', (string) $s);
	}

	/**
	 * The string shall not be formatted because we explicitely requested quoting.
	 */
	public function testExplicitQuoting()
	{
		$s = new FormattedString('Testing... :a', [ '%a' => 'A' ]);
		$this->assertEquals('Testing... :a', (string) $s);
	}

	public function testQuotingEscaped()
	{
		$s = new FormattedString('Testing... %a', [ 'a' => 'A<>' ]);
		$this->assertEquals('Testing... `A&lt;&gt;`', (string) $s);
	}

	public function testAsIs()
	{
		$s = new FormattedString('Testing... :a', [ 'a' => 'A<>' ]);
		$this->assertEquals('Testing... A<>', (string) $s);
	}
}
