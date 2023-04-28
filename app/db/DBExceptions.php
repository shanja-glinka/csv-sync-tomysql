<?

namespace DB;

class DBExceptions implements \Interfaces\IException
{
    public static function logit($exception, $topic = '')
    {
        $message = $exception->getMessage();
        $fileLog = 'logs/log_' . $topic . '.txt';

        clearstatcache();

        $fileTime = file_exists($fileLog) ? @filemtime($fileLog) : 0;
        $fileHandler = fopen($fileLog, 'a');

        if (!$fileHandler)
            return;

        $separatorTime = abs(time() - $fileTime);

        if (10 <= $separatorTime)
            fwrite($fileHandler, '- - - - - [' . gmdate('d.m.y H:i:s') . ($separatorTime <= 120 ? ' +' . $separatorTime : '') . '] - - - - -' . "\n");

        if (is_array($message) || is_object($message))
            $message = print_r($message, true);

        fwrite($fileHandler, '<' . $exception->getFile() . ' - on Line: ' . $exception->getLine() . '> ' . $message . "\n");
        fclose($fileHandler);
    }
}
