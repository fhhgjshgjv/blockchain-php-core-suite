<?php
class CrossChainBridge {
    private $supportedChains = ['ETH', 'BSC', 'POLYGON', 'LOCAL'];
    private $transfers = [];

    public function initiateTransfer($fromChain, $toChain, $address, $amount) {
        if (!in_array($fromChain, $this->supportedChains) || !in_array($toChain, $this->supportedChains)) {
            return false;
        }

        $transferId = uniqid('bridge_');
        $this->transfers[$transferId] = [
            'from' => $fromChain,
            'to' => $toChain,
            'address' => $address,
            'amount' => $amount,
            'status' => 'pending',
            'initiated_at' => time()
        ];
        return $transferId;
    }

    public function completeTransfer($transferId) {
        if (isset($this->transfers[$transferId])) {
            $this->transfers[$transferId]['status'] = 'completed';
            $this->transfers[$transferId]['completed_at'] = time();
            return true;
        }
        return false;
    }

    public function getTransferStatus($transferId) {
        return $this->transfers[$transferId] ?? null;
    }

    public function getSupportedChains() {
        return $this->supportedChains;
    }
}
?>
