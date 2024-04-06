<?php
namespace app\core;

class Logger
{
    // Define the path to the log file
    const LOG_FILE_PATH = ROOT. '/logs/app.log';

    public function log(string $message)
    {
        // Add timestamp to the message
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message";

        // Check if the log file directory exists, if not, create it
        $logDirectory = dirname(self::LOG_FILE_PATH);
        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, 0777, true); // Create the directory recursively
        }

        // Check if the log file exists or can be created
        if (!$this->ensureLogFileExists()) {
            // Unable to create or access the log file, log to console or another fallback
            $this->logToFallback($logMessage);
            return;
        }

        // Append the message to the log file
        file_put_contents(self::LOG_FILE_PATH, $logMessage . PHP_EOL, FILE_APPEND);
    }

    private function ensureLogFileExists(): bool
    {
        if (!file_exists(self::LOG_FILE_PATH)) {
            // Attempt to create the log file
            $handle = fopen(self::LOG_FILE_PATH, 'w');
            if ($handle === false) {
                // Failed to create the log file
                return false;
            }
            fclose($handle);
        }
        // Log file exists or was successfully created
        return true;
    }    
}
