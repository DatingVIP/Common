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

use Brickrouge\format;
/**
 * Exception thrown when there is something wrong with an array offset.
 *
 * This is the base class for offset exceptions, one should rather use the
 * {@link OffsetNotReadable} or {@link OffsetNotWritable} exceptions.
 */
class OffsetError extends \RuntimeException
{

}

/**
 * Exception thrown when an offset is not defined.
 *
 * For example, this could be triggered by an offset out of bounds while setting an array value.
 */
class OffsetNotDefined extends PropertyError
{
	public function __construct($message, $code=500, \Exception $previous=null)
	{
		if (is_array($message))
		{
			list($offset, $container) = $message + array(1 => null);

			if (is_object($container))
			{
				$message = format
				(
					'Undefined offset %offset for object of class %class.', array
					(
						'%offset' => $offset,
						'%class' => get_class($container)
					)
				);
			}
			else if (is_array($container))
			{
				$message = format
				(
					'Undefined offset %offset for the array: !array', array
					(
						'%offset' => $offset,
						'!array' => $container
					)
				);
			}
			else
			{
				$message = format
				(
					'Undefined offset %offset.', array
					(
						'%offset' => $offset
					)
				);
			}
		}

		parent::__construct($message, $code, $previous);
	}
}

/**
 * Exception thrown when an array offset is not readable.
 */
class OffsetNotReadable extends OffsetError
{
	public function __construct($message, $code=500, \Exception $previous=null)
	{
		if (is_array($message))
		{
			list($offset, $container) = $message + array(1 => null);

			if (is_object($container))
			{
				$message = format
				(
					'The offset %offset for object of class %class is not readable.', array
					(
						'offset' => $offset,
						'class' => get_class($container)
					)
				);
			}
			else if (is_array($container))
			{
				$message = format
				(
					'The offset %offset is not readable for the array: !array', array
					(
						'offset' => $offset,
						'array' => $container
					)
				);
			}
			else
			{
				$message = format
				(
					'The offset %offset is not readable.', array
					(
						'offset' => $offset
					)
				);
			}
		}

		parent::__construct($message, $code, $previous);
	}
}

/**
 * Exception thrown when an array offset is not writable.
 */
class OffsetNotWritable extends OffsetError
{
	public function __construct($message, $code=500, \Exception $previous=null)
	{
		if (is_array($message))
		{
			list($offset, $container) = $message + array(1 => null);

			if (is_object($container))
			{
				$message = format
				(
					'The offset %offset for object of class %class is not writable.', array
					(
						'offset' => $offset,
						'class' => get_class($container)
					)
				);
			}
			else if (is_array($container))
			{
				$message = format
				(
					'The offset %offset is not writable for the array: !array', array
					(
						'offset' => $offset,
						'array' => $container
					)
				);
			}
			else
			{
				$message = format
				(
					'The offset %offset is not writable.', array
					(
						'offset' => $offset
					)
				);
			}
		}

		parent::__construct($message, $code, $previous);
	}
}

/**
 * Exception thrown when there is something wrong with an object property.
 *
 * This is the base class for property exceptions, one should rather use the
 * {@link PropertyNotDefined}, {@link PropertyNotReadable} or {@link PropertyNotWritable}
 * exceptions.
 *
 * @property-read string $property The property that triggered the error.
 * @property-read object $container The container which property triggered the error.
 * @property-read PropertyErrorInfo The error information passed during construct.
 */
class PropertyError extends \RuntimeException
{
	/**
	 * Error information.
	 *
	 * @var PropertyErrorInfo
	 */
	private $error_info;

	/**
	 * Initializes the {@link $error_info}, {@link $code} and {@link $previous} properties.
	 *
	 * @param PropertyErrorInfo $error_info
	 * @param number $code
	 * @param \Exception $previous
	 */
	public function __construct(PropertyErrorInfo $error_info, $code=500, \Exception $previous=null)
	{
		$this->error_info = $error_info;

		parent::__construct((string) $error_info, $code, $previous);
	}

	/**
	 * Support for the following magic property:
	 *
	 * - `property`
	 * - `container`
	 * - `error_info`
	 *
	 * @param string $property
	 *
	 * @throws PropertyNotDefined in attempt to get an unsupported property.
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		switch ($property)
		{
			case 'property':

				return $this->error_info->property;

			case 'container':

				return $this->error_info->container;

			case 'error_info':

				return $this->error_info;
		}

		throw new PropertyNotDefined(new PropertyErrorInfo($property, $this), $this->getCode(), $this);
	}
}

/**
 * Information about a property error.
 */
class PropertyErrorInfo
{
	static public function from($source)
	{
		if (is_string($source) || (is_object($source) && method_exists($source, '__toString')))
		{
			return new static(null, null, (string) $source);
		}

		if (is_array($source))
		{
			$source += array(null, null, null);

			return new static($source[0], $source[1], $source[2]);
		}

		throw new \BadMethodCallException("Unable to create instance from source.");
	}

	protected $property;
	protected $container;
	public $message;

	public function __construct($property, $container=null, $message=null)
	{
		$this->property = $property;
		$this->container = $container;
		$this->message = $message;
	}

	public function __get($property)
	{
		switch ($property)
		{
			case 'property':

				return $this->property;

			case 'container':

				return $this->container;

			case 'message':

				return $this->message;
		}

		throw new PropertyNotDefined(new PropertyErrorInfo($property, $this));
	}

	public function __toString()
	{
		$container = $this->container;

		return format($this->message, array(

			'property' => $this->property,
			'class' => is_object($container) ? get_class($container) : gettype($container)

		));
	}
}

/**
 * Exception thrown when a property is not defined.
 *
 * For example, this could be triggered by getting the value of an undefined property.
 */
class PropertyNotDefined extends PropertyError
{
	public function __construct($error_info, $code=500, \Exception $previous=null)
	{
		if (!($error_info instanceof PropertyErrorInfo))
		{
			$error_info = PropertyErrorInfo::from($error_info);
		}

		$property = $error_info->property;
		$message = &$error_info->message;

		if ($property && !$message)
		{
			if (is_object($error_info->container))
			{
				$message = 'Undefined property %property for object of class %class.';
			}
			else
			{
				$message = 'Undefined property %property.';
			}
		}

		parent::__construct($error_info, $code, $previous);
	}
}

/**
 * Exception thrown when a property is not readable.
 *
 * For example, this could be triggered when a private property is read from a public scope.
 */
class PropertyNotReadable extends PropertyError
{
	public function __construct($error_info, $code=500, \Exception $previous=null)
	{
		if (!($error_info instanceof PropertyErrorInfo))
		{
			$error_info = PropertyErrorInfo::from($error_info);
		}

		$property = $error_info->property;
		$message = &$error_info->message;

		if ($property && !$message)
		{
			if (is_object($error_info->container))
			{
				$message = 'The property %property for object of class %class is not readable.';
			}
			else
			{
				$message = 'The property %property is not readable.';
			}
		}

		parent::__construct($error_info, $code, $previous);
	}
}

/**
 * Exception thrown when a property is not writable.
 *
 * For example, this could be triggered when a private property is written from a public scope.
 */
class PropertyNotWritable extends PropertyError
{
	public function __construct($error_info, $code=500, \Exception $previous=null)
	{
		if (!($error_info instanceof PropertyErrorInfo))
		{
			$error_info = PropertyErrorInfo::from($error_info);
		}

		$property = $error_info->property;
		$message = &$error_info->message;

		if ($property && !$message)
		{
			if (is_object($error_info->container))
			{
				$message = 'The property %property for object of class %class is not writable.';
			}
			else
			{
				$message = 'The property %property is not writable.';
			}
		}

		parent::__construct($error_info, $code, $previous);
	}
}

/**
 * Exception thrown when a property has a reserved name.
 *
 * @property-read string $property Name of the reserved property.
 */
class PropertyIsReserved extends PropertyError
{
	public function __construct($property, $code=500, \Exception $previous=null)
	{
		parent::__construct(new PropertyErrorInfo($property, null, 'Property %property is reserved.'), $code, $previous);
	}
}