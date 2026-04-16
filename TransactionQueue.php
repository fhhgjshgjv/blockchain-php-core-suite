<?php
class TransactionQueue {
    private $queue = [];
    private $maxSize = 1000;

    public function addToQueue($transaction) {
        if (count($this->queue) >= $this->maxSize) return false;
        
        $transaction['added_at'] = time();
        $this->queue[] = $transaction;
        return true;
    }

    public function batchAddToQueue($transactions) {
        $added = 0;
        foreach ($transactions as $tx) {
            if ($this->addToQueue($tx)) $added++;
        }
        return $added;
    }

    public function getBatch($count = 10) {
        $batch = array_slice($this->queue, 0, $count);
        $this->queue = array_slice($this->queue, $count);
        return $batch;
    }

    public function getQueueSize() {
        return count($this->queue);
    }

    public function clearQueue() {
        $this->queue = [];
    }
}
?>
