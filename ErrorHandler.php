<?php
class ErrorHandler {
    private $logs = [];
    private $logFile = 'blockchain_errors.log';

    public function __construct() {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    public function handleError($errno, $errstr, $errfile, $errline) {
        $error = [
            'type' => 'error',
            'code' => $errno,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'time' => time()
        ];
        $this->logs[] = $error;
        $this->writeToFile($error);
    }

    public function handleException($exception) {
        $error = [
            'type' => 'exception',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'time' => time()
        ];
        $this->logs[] = $error;
        $this->writeToFile($error);
    }

    private function writeToFile($data) {
        file_put_contents($this->logFile, json_encode($data) . PHP_EOL, FILE_APPEND);
    }

    public function getLogs() {
        return $this->logs;
    }
}
?>
