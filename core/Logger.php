<?php
namespace Core;

class Logger {
    private $logFile;
    private $sqlLogFile;

    public function __construct($logFile = 'site_errors.log', $sqlLogFile = 'sql_queries.log') {
        $this->logFile = $logFile;
        $this->sqlLogFile = $sqlLogFile;
       $this->logFolder();
        
    }

    // Function Pour verifier que le dossier existe sinon on le cree avec les permissions
    private function logFolder(){
        $base=HTDOCS."/logs";
        if (!file_exists($base)) {
            mkdir($base, 0777, true);
        }
    }

    // Enregistrer une erreur dans le fichier de logs
    public function logError($errorMessage) {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] ERROR: " . $errorMessage . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    // Enregistrer une requête SQL dans le fichier de logs
    public function logSQLQuery($sqlQuery) {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] SQL QUERY: " . $sqlQuery . PHP_EOL;
        file_put_contents($this->sqlLogFile, $logMessage, FILE_APPEND);
    }
}



