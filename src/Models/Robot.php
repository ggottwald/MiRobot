<?php

namespace MiRobot\Models;

use MiIO\Models\Device;


/**
 * Class MiRobot
 *
 * @package MiRobot\Models
 */
class Robot extends Device
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Robot
	 */
	public function setName(string $name): Robot
	{
		$this->name = $name;

		return $this;
	}
}