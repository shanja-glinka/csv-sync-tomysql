<?

namespace Cron;

class CronCSV implements \Interfaces\ICronCSV
{
    public function isCronTime($lastUpdate, $interval = 120)
    {
        return time() < $lastUpdate + $interval;
    }

    public function start($connectConfig, $csvConfig  )
    {
        $csvLoader = new \CSV\Loader();
        $csvParser = new \CSV\Parser();
        $csvDBLoader = new \CSV\DBUpdater($connectConfig, $csvConfig['tableName']);


        $csvContent = $csvLoader($csvConfig['path']);
        $csvContent = $csvParser->splitCSV($csvContent);


        return $csvDBLoader->makeUpdate($csvContent);
    }
}
