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

use Throwable;

/**
 * Exception thrown when an array offset is not readable.
 */
class OffsetNotReadable extends OffsetError
{
	public function __construct(string|array $message, Throwable $previous = null)
	{
		if (is_array($message))
		{
			[ $offset, $container ] = $message + [ 1 => null ];

			if (is_object($container))
			{
				$message = format('The offset %offset for object of class %class is not readable.', [
					'offset' => $offset,
					'class' => get_class($container),
				]);
			}
			elseif (is_array($container))
			{
				$message = format('The offset %offset is not readable for the array: !array', [
					'offset' => $offset,
					'array' => $container,
				]);
			}
			else
			{
				$message = format('The offset %offset is not readable.', [
					'offset' => $offset,
				]);
			}
		}

		parent::__construct($message, 0, $previous);
	}
}
