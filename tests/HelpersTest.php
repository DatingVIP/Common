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

use PHPUnit\Framework\TestCase;

use function ICanBoogie\array_flatten;
use function ICanBoogie\escape;
use function ICanBoogie\format;
use function ICanBoogie\remove_accents;
use function ICanBoogie\sort_by_weight;

/**
 * @group unit
 */
final class HelpersTest extends TestCase
{
	public function test_escape()
	{
		$this->assertSame("&amp;&quot;'&lt;ab&gt;cd", escape('&"\'<ab>cd'));
	}

	public function array_provider(): array
	{
		return [
			[
				[
					'one' =>
						[
							'one' => 1,
							'two' =>
								[
									'one' => 1,
									'two' => 2,
									'three' =>
										[
											'one' => 1,
											'two' => 2,
											'three' => 3,
										],
								],

							'three' =>
								[
									'one' =>
										[
											'one' => 1,
											'two' => 2,
											'three' => 3,
										],

									'two' =>
										[
											'one' => 1,
											'two' => 2,
											'three' => 3,
										],

									'three' => 3,
								],
						],

					'two' =>
						[
							'one' => 1,
							'two' => 2,
							'three' => 3,
						],
				],
			],
		];
	}

	/**
	 * @dataProvider array_provider()
	 */
	public function test_array_flatten(array $data): void
	{
		$flat = array_flatten($data);

		$this->assertEquals
		(
			[
				'one.one' => 1,
				'one.two.one' => 1,
				'one.two.two' => 2,
				'one.two.three.one' => 1,
				'one.two.three.two' => 2,
				'one.two.three.three' => 3,
				'one.three.one.one' => 1,
				'one.three.one.two' => 2,
				'one.three.one.three' => 3,
				'one.three.two.one' => 1,
				'one.three.two.two' => 2,
				'one.three.two.three' => 3,
				'one.three.three' => 3,
				'two.one' => 1,
				'two.two' => 2,
				'two.three' => 3,
			],

			$flat
		);
	}

	/**
	 * @dataProvider array_provider()
	 */
	public function test_array_flatten_double(array $data): void
	{
		$flat = array_flatten($data, [ '[', ']' ]);

		$this->assertEquals
		(
			[
				'one[one]' => 1,
				'one[two][one]' => 1,
				'one[two][two]' => 2,
				'one[two][three][one]' => 1,
				'one[two][three][two]' => 2,
				'one[two][three][three]' => 3,
				'one[three][one][one]' => 1,
				'one[three][one][two]' => 2,
				'one[three][one][three]' => 3,
				'one[three][two][one]' => 1,
				'one[three][two][two]' => 2,
				'one[three][two][three]' => 3,
				'one[three][three]' => 3,
				'two[one]' => 1,
				'two[two]' => 2,
				'two[three]' => 3,
			],

			$flat
		);
	}

	/**
	 * @dataProvider provide_test_sort_by_weight
	 */
	public function test_sort_by_weight(array $array, array $expected): void
	{
		$this->assertSame($expected, sort_by_weight($array, function ($v) {
			return $v;
		}));
	}

	public function provide_test_sort_by_weight(): array
	{
		return [
			#1

			[
				[
					'bottom' => 'bottom',
					'min' => -10000,
					'max' => 10000,
					'top' => 'top',
				],

				[
					'top' => 'top',
					'min' => -10000,
					'max' => 10000,
					'bottom' => 'bottom',
				],
			],

			#3: missing relative

			[
				[
					'two' => 2,
					'one' => 1,
					'four' => 'after:three',
				],

				[
					'four' => 'after:three',
					'one' => 1,
					'two' => 2,
				],
			],

			#2

			[
				[
					'two' => 0,
					'three' => 0,
					'bottom' => 'bottom',
					'megabottom' => 'bottom',
					'hyperbottom' => 'bottom',
					'one' => 'before:two',
					'four' => 'after:three',
					'top' => 'top',
					'megatop' => 'top',
					'hypertop' => 'top',
				],

				[
					'hypertop' => 'top',
					'megatop' => 'top',
					'top' => 'top',
					'one' => 'before:two',
					'two' => 0,
					'three' => 0,
					'four' => 'after:three',
					'bottom' => 'bottom',
					'megabottom' => 'bottom',
					'hyperbottom' => 'bottom',
				],
			],
		];
	}

	/**
	 * @dataProvider provide_test_remove_accents
	 */
	public function test_remove_accents(string $expected, string $str): void
	{
		$this->assertEquals($expected, remove_accents($str));
	}

	public function provide_test_remove_accents(): array
	{
		return [

			[ 'AAAAAAAE', 'ÁÀÂÄÃÅÆ' ],
			[ 'aaaaaaae', 'áàâäãåæ' ],
			[ 'C', 'Ç' ],
			[ 'c', 'ç' ],
			[ 'EEEE', 'ÉÈÊË' ],
			[ 'eeee', 'éèêë' ],
			[ 'IIII', 'ÍÏÎÌ' ],
			[ 'iiii', 'íìîï' ],
			[ 'N', 'Ñ' ],
			[ 'n', 'ñ' ],
			[ 'OOOOO', 'ÓÒÔÖÕ' ],
			[ 'oooooo', 'óòôöõø' ],
			[ 'S', 'Š' ],
			[ 's', 'š' ],
			[ 'UUUU', 'ÚÙÛÜ' ],
			[ 'uuuu', 'úùûü' ],
			[ 'YY', 'ÝŸ' ],
			[ 'yy', 'ýÿ' ],

		];
	}

	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format(string $format, array $args, string $expected): void
	{
		$this->assertEquals($expected, format($format, $args));
	}

	public function provide_test_format(): array
	{
		return [

			[ "abc \\0", [ '<def' ], "abc <def" ],
			[ "abc {0}", [ '<def' ], "abc <def" ],
			[ "abc {a1}", [ 'a1' => '<def' ], "abc &lt;def" ],
			[ "abc :a1", [ 'a1' => '<def' ], "abc <def" ],
			[ "abc !a1", [ 'a1' => '<def' ], "abc &lt;def" ],
			[ "abc %a1", [ 'a1' => '<def' ], "abc `&lt;def`" ],

		];
	}
}
