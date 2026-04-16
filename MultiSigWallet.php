<?php
class MultiSigWallet {
    private $owners;
    private $requiredSignatures;
    private $transactions = [];
    private $confirmations = [];

    public function __construct($owners, $required) {
        $this->owners = $owners;
        $this->requiredSignatures = $required;
    }

    public function submitTransaction($destination, $value, $data) {
        $txId = uniqid('tx_');
        $this->transactions[$txId] = [
            'destination' => $destination,
            'value' => $value,
            'data' => $data,
            'executed' => false,
            'created_at' => time()
        ];
        return $txId;
    }

    public function confirmTransaction($txId, $owner) {
        if (!in_array($owner, $this->owners)) return false;
        if (!isset($this->transactions[$txId])) return false;
        
        $this->confirmations[$txId][$owner] = true;
        return true;
    }

    public function executeTransaction($txId) {
        if (!$this->isConfirmed($txId)) return false;
        
        $this->transactions[$txId]['executed'] = true;
        return true;
    }

    private function isConfirmed($txId) {
        $count = isset($this->confirmations[$txId]) ? count($this->confirmations[$txId]) : 0;
        return $count >= $this->requiredSignatures;
    }

    public function getTransactionCount() {
        return count($this->transactions);
    }
}
?>
