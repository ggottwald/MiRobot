<?php

namespace MiRobot;

use MiRobot\Models\Consumable;
use MiRobot\Models\Robot;
use MiRobot\Models\Status;
use MiIO\MiIO;
use MiIO\Models\Response;
use React\Promise\Promise;
use Socket\Raw\Factory;

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
    private $commandList = [
        'start'         => self::START_VACUUM,
        'stop'          => self::STOP_VACUUM,
        'pause'         => self::PAUSE,
        'charge'        => self::CHARGE,
        'find'          => self::FIND_ME,
        'status'        => self::GET_STATUS,
        'getConsumable' => self::CONSUMABLES_GET,
        'startSpot'     => self::START_SPOT,
    ];

    /**
     * @var Factory
     */
    protected $socketFactory;

    public function __construct(Factory $socketFactory, MiIO $miIO)
    {
        $this->socketFactory = $socketFactory;
        $this->miIO = $miIO;
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return Robot
     */
    public function createRobot(string $deviceName, string $token): Robot
    {
        return new Robot($this->socketFactory->createUdp4(), $deviceName, $token);
    }

    /**
     * @param Robot $robot
     * @return Promise
     */
    public function setMode(Robot $robot): Promise
    {
        $mode = 60;
        $this->miIO->send($robot, 'get_custom_mode')
            ->done(function ($response) use (&$mode) {
                if ($response instanceof Response) {
                    $mode = $response->getResult()[0];
                }
            }, function ($rejected) {

            });

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

        return $this->miIO->send($robot, 'set_custom_mode', [$mode]);
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return Status|Consumable|null
     */
    public function __call($name, $arguments)
    {
        $result = null;

        if (array_key_exists($name, $this->commandList)
            && ($robot = $arguments[0]) instanceof Robot) {

            $this->miIO->send($robot, $this->commandList[$name])
                ->done(function ($response) use ($name, &$result) {
                    if ($response instanceof Response) {
                        switch ($name) {
                            case 'status':
                                $result = new Status($response->getResult()[0]);
                                break;
                            case 'getConsumable':
                                $result = new Consumable($response->getResult()[0]);
                        }
                    }
                }, function ($rejected) {
                    // TODO: error handling
                });
        }

        return $result;
    }
}