<?

namespace Cron;

use Exception;

class CronUpdate implements \Interfaces\ICronUpdate
{
    public $tableName;

    protected $interval = 120;

    private $connection;
    private $lastUpdateProp;
    private $lockProp;

    public function __construct($connectConfig, $interval = null)
    {
        $this->connection = new \DB\Connection($connectConfig);

        if ($interval !== null)
            $this->interval = $interval;

            
        $this->tableName = 'Config';

        $this->lastUpdateProp = 'CronLT';
        $this->lockProp = 'CronLock';
    }

    public function tryStart($startfuncCall, $args)
    {
        if ($this->isLocked($this->getLastUpdate(), $this->interval))
            return $this->croneLocked();


        $this->lockCron();

        try {
            call_user_func_array($startfuncCall, $args);
        } catch (\Exception $ex) {

            $this->croneDone();

            throw new \Exception('CronStart failed with error', 500);
        }

        $this->croneDone();
    }


    public function isLocked()
    {
        return ($this->getLockState() == 1);
    }


    public function lockCron()
    {
        return $this->updateLock(1);
    }

    public function unlockCron()
    {
        return $this->updateLock(0);
    }



    public function getLockState()
    {
        return $this->connection->fetch1($this->connection->select($this->tableName, 'Val', 'Prop=?', array($this->lockProp)));
    }

    public function getLastUpdate()
    {
        return $this->connection->fetch1($this->connection->select($this->tableName, 'Val', 'Prop=?', array($this->lastUpdateProp)));
    }

    public function setLastUpdate($updatedTime)
    {
        if ($this->getLastUpdate() == null)
            return $this->connection->insert($this->tableName, array('Prop' => $this->lastUpdateProp, 'Val' => $updatedTime), '');
        else
            return $this->connection->update($this->tableName, array('Val' => $updatedTime), '', 'Prop=?', array($this->lastUpdateProp));
    }



    public function croneDone()
    {
        $updatedTime = time();

        $this->setLastUpdate($updatedTime);
        $this->unlockCron();

        print('cron updated: ' . $updatedTime);
    }

    public function croneFailed($seconds)
    {
        print('cron failed. Try after: ' . $seconds . 's');
    }


    public function croneLocked()
    {
        print('cron locked. Try next time');
    }




    private function updateLock($lockState = 0)
    {
        if ($this->getLockState() == null)
            return $this->connection->insert($this->tableName, array('Prop' => $this->lockProp, 'Val' => $lockState), '');
        else
            return $this->connection->update($this->tableName, array('Val' => $lockState), '', 'Prop=?', array($this->lockProp));
    }
}
