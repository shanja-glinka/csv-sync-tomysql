<?

namespace Interfaces;

interface ICronUpdate
{
    public function tryStart($startfuncCall, $args);

    public function isLocked();
    public function lockCron();
    public function unlockCron();
    
    public function croneDone();
}
