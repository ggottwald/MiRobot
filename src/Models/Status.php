<?php

namespace MiRobot\Models;

/**
 * Class Status
 *
 * @package MiRobot\Models
 *
 * @property string $state_message Status message
 */
class Status
{
	private static $statusCodes = [
		0   => 'Unknown',
		1   => 'Initiating',
		2   => 'Sleeping',
		3   => 'Waiting',
		4   => '?',
		5   => 'Cleaning',
		6   => 'Back to home',
		7   => '?',
		8   => 'Charging',
		9   => 'Charging Error',
		10  => 'Pause',
		11  => 'Spot Cleaning',
		12  => 'In Error',
		13  => 'Shutting down',
		14  => 'Updating',
		15  => 'Docking',
		100 => 'Full',
	];

	/**
	 * Battery level
	 *
	 * @var int
	 */
	public $battery;

	/**
	 * total area (in cm2)
	 *
	 * @var int
	 */
	public $clean_area;

	/**
	 * Total cleaning time in sec
	 *
	 * @var int
	 */
	public $clean_time;

	/**
	 * Is Do Not Disturb enabled (0=disabled)
	 *
	 * @var int
	 */
	public $dnd_enabled;

	/**
	 * Error code (0=no error. see list below)
	 *
	 * @var int
	 */
	public $error_code;

	/**
	 * Fan power
	 *
	 * @var int
	 */
	public $fan_power;

	/**
	 * Is device cleaning
	 *
	 * @var int
	 */
	public $in_cleaning;

	/**
	 * Is map present
	 *
	 * @var int
	 */
	public $map_present;

	/**
	 * Message sequence increments with each request
	 *
	 * @var int
	 */
	public $msg_seq;

	/**
	 * Message version (seems always 4)
	 *
	 * @var int
	 */
	public $msg_ver;

	/**
	 * Status code (see list below)
	 *
	 * @var int
	 */
	public $state;

	public function __construct($attributes = [])
	{
		$this->fill($attributes);
	}

	/**
	 * @param array $attributes
	 */
	private function fill($attributes)
	{
		foreach ($attributes as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if ($key == 'state_message') {
			return static::$statusCodes[$this->state] ?? $this->state;
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $this->$name !== null;
	}
}