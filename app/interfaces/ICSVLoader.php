<?

namespace Interfaces;

interface ICSVLoader
{
    public function loadCVS($csvPath);
    public function remoteLoadCSV($csvPath);
}
