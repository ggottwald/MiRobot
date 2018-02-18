<?php

namespace MiRobot;

use MiRobot\Models\Consumable;
use MiRobot\Models\Robot;
use MiRobot\Models\Status;
use MiIO\MiIO;

/**
 * Class MiRobot
 *
 * @package MiRobot
 *
 * @method start(Robot $robot)
 * @method stop(Robot $robot)
 * @method pause(Robot $robot)
 * @method charge(Robot $robot)
 * @method find(Robot $robot)
 * @method startSpot(Robot $robot)
 * @method Status status(Robot $robot)
 * @method Consumable getConsumable(Robot $robot)
 */
class MiRobot
{
	const START_VACUUM = 'app_start'; // Start vacuuming
	const STOP_VACUUM = 'app_stop'; // Stop vacuuming
	const START_SPOT = 'app_spot'; // Start spot cleaning
	const PAUSE = 'app_pause'; // Pause cleaning
	const CHARGE = 'app_charge'; // Start charging
	const FIND_ME = 'find_me'; // Send findme
	const CONSUMABLES_GET = 'get_consumable'; // Get consumables status
	const CONSUMABLES_RESET = 'reset_consumable'; // Reset consumables
	const CLEAN_SUMMARY_GET = 'get_clean_summary'; // Cleaning details
	const GET_STATUS = 'get_status'; // Get Status information

	/**
	 * @var MiIO
	 */
	private $miIO;

	/**
	 * @var array
	 */
	private $commandList = [];

	public function __construct()
	{
		$this->miIO = new MiIO();

		$this->commandList = [
			'start'         => static::START_VACUUM,
			'stop'          => static::STOP_VACUUM,
			'pause'         => static::PAUSE,
			'charge'        => static::CHARGE,
			'find'          => static::FIND_ME,
			'status'        => static::GET_STATUS,
			'getConsumable' => static::CONSUMABLES_GET,
			'startSpot'     => static::START_SPOT,
		];
	}

	/**
	 * @param Robot $robot
	 */
	public function setMode(Robot $robot)
	{
		$response = $this->miIO->send($robot, 'get_custom_mode');

		$mode = $response['result'][0] ?? 60;

		switch ($mode) {
			case 60:
				$mode = 77;
				break;
			case 77:
				$mode = 90;
				break;
			case 90:
				$mode = 38;
				break;
			case 38:
			default:
				$mode = 60;
				break;
		}

		$this->miIO->send($robot, 'set_custom_mode', [$mode]);
	}

	/**
	 * @param string $name
	 * @param array  $arguments
	 * @return Status|Consumable|null
	 */
	public function __call($name, $arguments)
	{
		if (array_key_exists($name, $this->commandList)
			&& $arguments[0] instanceof Robot) {
			$response = $this->miIO->send($arguments[0], $this->commandList[$name]);

			switch ($name) {
				case 'status':
					if (isset($response['result'][0])) {
						return new Status($response['result'][0]);
					}
					break;
				case 'getConsumable':
					if (isset($response['result'][0])) {
						return new Consumable($response['result'][0]);
					}
					break;
			}
		}

		return null;
	}
}