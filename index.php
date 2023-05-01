<?

@include_once('error_output.php');

define('AppDirectory', str_replace('\\', '/', __DIR__) . '/app');

require_once('autoloader.php');


$config = require_once('config.php');


$connectConfig = $config['connection'];
$csvConfig = $config['csv'];


$cronUpdate = new \Cron\CronUpdate($connectConfig, $csvConfig['croninterval']);
$cronCSV = new \Cron\CronCSV();

$updateInterval = $csvConfig['croninterval'];
$cronLastTime = $cronUpdate->getLastUpdate();



if (!$cronCSV->isCronTime($cronLastTime, $updateInterval)) {
    $cronUpdate->tryStart([$cronCSV, 'start'], [$connectConfig, $csvConfig]);
} else
    $cronUpdate->croneFailed($cronLastTime + $updateInterval - time());
