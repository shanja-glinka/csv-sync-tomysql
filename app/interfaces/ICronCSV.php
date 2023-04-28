<?

namespace Interfaces;

interface ICronCSV
{
    public function start($connectConfig, $csvConfig);
    public function isCronTime($lastUpdate, $interval = 120);
}
