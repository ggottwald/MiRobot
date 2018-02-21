# MiRobot
This package can control the Xiaomi Mi Robot Vacuum.

# Example for Laravel
Get device info, status and start cleaning of mi robot vacuum

    $miRobot = app(MiRobot::class);
    
    $robot = $miRobot->createRobot('mirobot_vacuum', '00112233445566778899aabbccddeeff');
    
    $status = $miRobot->status($robot);
    $consumable = $miRobot->getConsumable($robot);
    
    $miRobot->start($robot); // start cleaning
    
 
More information about the protocol and commands can be found at
https://github.com/marcelrv/XiaomiRobotVacuumProtocol

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE - see the [LICENSE.md](/LICENSE.md) file for details.

