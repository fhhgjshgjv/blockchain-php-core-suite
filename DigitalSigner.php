<?php
class DigitalSigner {
    public function generateSignature($data, $privateKey) {
        $hash = hash('sha256', $data);
        $signature = hash_hmac('sha256', $hash, $privateKey);
        return $signature;
    }

    public function verifySignature($data, $signature, $publicKey) {
        $hash = hash('sha256', $data);
        $calculated = hash_hmac('sha256', $hash, $publicKey);
        return $calculated === $signature;
    }

    public function signTransaction($transaction, $privateKey) {
        $txData = json_encode($transaction);
        $signature = $this->generateSignature($txData, $privateKey);
        $transaction['signature'] = $signature;
        return $transaction;
    }

    public function verifyTransactionSignature($transaction) {
        $signature = $transaction['signature'];
        unset($transaction['signature']);
        $txData = json_encode($transaction);
        return $this->verifySignature($txData, $signature, $transaction['sender']);
    }
}
?>
