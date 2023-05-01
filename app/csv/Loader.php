<?

namespace CSV;

class Loader implements \Interfaces\ICSVLoader
{
    public function __invoke($csvpath)
    {
        if (strpos($csvpath, 'http://') === false and strpos($csvpath, 'https://') === false)
            return $this->loadCVS($csvpath);

        return $this->remoteLoadCSV($csvpath);
    }

    
    public function loadCVS($csvpath)
    {
        return $this->makeLoad($csvpath);
    }

    public function remoteLoadCSV($csvpath)
    {
        $opts = array(
            'http' =>
            array(
                'method'  => 'GET',
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
            )
        );

        $context = stream_context_create($opts);
        return $this->makeLoad($csvpath, $context);
    }



    private function makeLoad($path, $context = null)
    {
        if ($context === null) {
            if (!file_exists($path))
                throw new \Exception('File \'' . $path . '\' not found', 400);

            return file_get_contents($path);
        }
        return @file_get_contents($path, false, $context);
    }
}
