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

use function array_shift;
use function func_get_args;
use function is_array;

/**
 * A formatted string.
 *
 * The string is formatted by replacing placeholders with the values provided.
 */
class FormattedString
{
	/**
	 * String format.
	 */
	private string $format;

	/**
	 * An array of replacements for the placeholders.
	 */
	private array $args;

	/**
	 * Initializes the {@link $format} and {@link $args} properties.
	 *
	 * @param string $format String format.
	 * @param mixed|null $args Format arguments.
	 *
	 * @see format()
	 */
	public function __construct(string $format, mixed $args = null)
	{
		if (!is_array($args))
		{
			$args = func_get_args();
			array_shift($args);
		}

		$this->format = $format;
		$this->args = (array) $args;
	}

	/**
	 * Returns the string formatted with the {@link format()} function.
	 */
	public function __toString(): string
	{
		return format($this->format, $this->args);
	}
}
