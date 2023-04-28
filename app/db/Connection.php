<?

namespace DB;

class Connection extends CustomPDO
{
    private $pdo;
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config;

        $this->useConnect();
    }

    public function useConnect()
    {
        $this->connect($this->config);
    }

    private function connect($connection)
    {
        if (!$connection['host'] or !$connection['database'])
            return $this;
            
        $this->setConnect($connection['host'], $connection['database'], $connection['username'], $connection['password'], $connection['charset']);
        $this->open();
        return $this;
    }
}
