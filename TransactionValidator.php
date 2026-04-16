<?php
class TransactionValidator {
    private $walletLedger = [];

    public function validateTransaction($sender, $recipient, $amount, $chain) {
        if ($amount <= 0) return false;
        if ($sender === $recipient) return false;
        
        $senderBalance = $this->calculateBalance($sender, $chain);
        return $senderBalance >= $amount;
    }

    private function calculateBalance($address, $chain) {
        $balance = 0;
        foreach ($chain as $block) {
            foreach ($block['transactions'] as $tx) {
                if ($tx['recipient'] === $address) $balance += $tx['amount'];
                if ($tx['sender'] === $address) $balance -= $tx['amount'];
            }
        }
        return $balance;
    }

    public function batchValidateTransactions($transactions, $chain) {
        $results = [];
        foreach ($transactions as $tx) {
            $results[] = [
                'tx' => $tx,
                'valid' => $this->validateTransaction($tx['sender'], $tx['recipient'], $tx['amount'], $chain)
            ];
        }
        return $results;
    }
}
?>
