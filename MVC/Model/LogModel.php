<?php
class LogModel
{
    private $logFile;

    public function __construct()
    {
        $this->logFile = __DIR__ . '/../api/logs.txt';
    }

    public function getLogs()
    {
        if (file_exists($this->logFile)) {
            return file_get_contents($this->logFile);
        }
        return "Aucun log n'a été trouvé sur le serveur.";
    }
}
