<?

define('AppDirectory', str_replace('\\', '/', __DIR__) . '/app');

require_once('autoloader.php');


$config = require_once('config.php');


$connectConfig = $config['connection'];
$csvConfig = $config['csv'];


$cronUpdate = new \CronUpdate($connectConfig);
$cronCSV = new \CronCSV();

$updateInterval = $csvConfig['croninterval'];
$cronLastTime = $cronUpdate->getLastUpdate();



// if (!$cronCSV->isCronTime($cronLastTime, $updateInterval)) {
    $cronCSV->start($connectConfig, $csvConfig);
    $cronUpdate->croneDone();
// } else 
//     $cronUpdate->croneFailed($cronLastTime + $updateInterval - time());
   
