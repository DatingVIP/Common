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
use function get_class;
use function is_array;
use function is_object;

/**
 * Exception thrown when a property is not defined.
 *
 * For example, this could be triggered by getting the value of an undefined property.
 */
class PropertyNotDefined extends PropertyError
{
	public function __construct(string|array $message, Throwable $previous = null)
	{
		if (is_array($message))
		{
			[ $property, $container ] = $message + [ 1 => null ];

			if (is_object($container))
			{
				$message = format('Undefined property %property for object of class %class.', [
					'%property' => $property,
					'%class' => get_class($container),
				]);
			}
			else
			{
				$message = format('Undefined property %property.', [
					'%property' => $property,
				]);
			}
		}

		parent::__construct($message, 0, $previous);
	}
}
