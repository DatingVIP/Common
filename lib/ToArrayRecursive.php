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

/**
 * An interface for an object that can be converted to an array, recursively.
 */
interface ToArrayRecursive extends ToArray
{
	/**
	 * Converts the object into an array recursively.
	 */
	public function to_array_recursive(): array;
}
