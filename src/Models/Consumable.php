<?php

namespace MiRobot\Models;

/**
 * Class Consumable
 *
 * @package MiRobot\Models
 *
 * @property int $main_brush_remain_work_time_hours
 * @property int $main_brush_work_time_percent
 * @property int $side_brush_remain_work_time_hours
 * @property int $side_brush_work_time_percent
 * @property int $filter_remain_work_time_hours
 * @property int $filter_work_time_percent
 * @property int $sensor_remain_dirty_time_hours
 * @property int $sensor_dirty_time_percent
 */
class Consumable
{
	/**
	 * time in hours
	 *
	 * @var array
	 */
	private static $workCycleList = [
		'main_brush_work_time' => 300,
		'side_brush_work_time' => 200,
		'filter_work_time'     => 150,
		'sensor_dirty_time'    => 30,
	];

	/**
	 * @var int
	 */
	public $main_brush_work_time;

	/**
	 * @var int
	 */
	public $side_brush_work_time;

	/**
	 * @var int
	 */
	public $filter_work_time;

	/**
	 * @var int
	 */
	public $sensor_dirty_time;

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
		switch ($key) {
			case 'main_brush_remain_work_time_hours':
				$hours = floor($this->main_brush_work_time / 60 / 60);

				return static::$workCycleList['main_brush_work_time'] - $hours;

			case 'main_brush_work_time_percent':
				return $this->getPercent('main_brush_work_time');

			case 'side_brush_remain_work_time_hours':
				$hours = floor($this->side_brush_work_time / 60 / 60);

				return static::$workCycleList['side_brush_work_time'] - $hours;

			case 'side_brush_work_time_percent':
				return $this->getPercent('side_brush_work_time');

			case 'filter_remain_work_time_hours':
				$hours = floor($this->filter_work_time / 60 / 60);

				return static::$workCycleList['filter_work_time'] - $hours;

			case 'filter_work_time_percent':
				return $this->getPercent('filter_work_time');

			case 'sensor_remain_dirty_time_hours':
				$hours = floor($this->sensor_dirty_time / 60 / 60);

				return static::$workCycleList['sensor_dirty_time'] - $hours;

			case 'sensor_dirty_time_percent':
				return $this->getPercent('sensor_dirty_time');
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

	/**
	 * @param string $attribute
	 * @return float
	 */
	private function getPercent($attribute)
	{
		$hours = floor($this->$attribute / 60 / 60);

		return floor(100 - ($hours * 100 / static::$workCycleList[$attribute]));
	}
}