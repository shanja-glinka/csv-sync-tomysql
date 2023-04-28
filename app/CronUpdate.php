<?

class CronUpdate
{
    public $tableName;

    private $connection;
    private $lastUpdateProp;

    public function __construct($connectConfig)
    {
        $this->connection = new \DB\Connection($connectConfig);
        $this->tableName = 'Config';

        $this->lastUpdateProp = 'CronLT';
    }

    public function getLastUpdate()
    {
        return $this->connection->fetch1($this->connection->select($this->tableName, 'Val', 'Prop=?', array($this->lastUpdateProp)));
    }

    public function setLastUpdate($updatedTime)
    {
        if ($this->getLastUpdate() == null)
            return $this->connection->insert($this->tableName, array('Val' => $updatedTime), '');
        else
            return $this->connection->update($this->tableName, array('Val' => $updatedTime), '', 'Prop=?', array($this->lastUpdateProp));
    }


    public function croneDone()
    {
        $updatedTime = time();

        $this->setLastUpdate($updatedTime);

        print('cron updated: ' . $updatedTime);
    }

    public function croneFailed($seconds)
    {
        print('cron failed. Try after: ' . $seconds . 's');
    }
}
