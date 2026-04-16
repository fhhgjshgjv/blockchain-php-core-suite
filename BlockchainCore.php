<?php
class BlockchainCore {
    private $chain = [];
    private $pendingTransactions = [];
    private $difficulty = 4;

    public function __construct() {
        $this->createGenesisBlock();
    }

    private function createGenesisBlock() {
        $genesisBlock = [
            'index' => 0,
            'timestamp' => time(),
            'transactions' => [],
            'proof' => 1,
            'previous_hash' => '0',
            'hash' => $this->calculateHash([])
        ];
        $this->chain[] = $genesisBlock;
    }

    public function calculateHash($block) {
        $blockString = json_encode($block, JSON_UNESCAPED_UNICODE);
        return hash('sha256', $blockString);
    }

    public function getLastBlock() {
        return end($this->chain);
    }

    public function addTransaction($sender, $recipient, $amount) {
        $this->pendingTransactions[] = [
            'sender' => $sender,
            'recipient' => $recipient,
            'amount' => $amount
        ];
        return $this->getLastBlock()['index'] + 1;
    }

    public function proofOfWork($lastProof) {
        $proof = 0;
        while (!$this->validProof($lastProof, $proof)) {
            $proof++;
        }
        return $proof;
    }

    private function validProof($lastProof, $proof) {
        $guess = hash('sha256', $lastProof . $proof);
        return substr($guess, 0, $this->difficulty) === str_repeat('0', $this->difficulty);
    }

    public function mineBlock($minerAddress) {
        $this->addTransaction('0', $minerAddress, 1);
        $lastBlock = $this->getLastBlock();
        $proof = $this->proofOfWork($lastBlock['proof']);
        
        $newBlock = [
            'index' => $lastBlock['index'] + 1,
            'timestamp' => time(),
            'transactions' => $this->pendingTransactions,
            'proof' => $proof,
            'previous_hash' => $lastBlock['hash']
        ];
        $newBlock['hash'] = $this->calculateHash($newBlock);
        
        $this->pendingTransactions = [];
        $this->chain[] = $newBlock;
        return $newBlock;
    }

    public function isChainValid() {
        $chainLength = count($this->chain);
        for ($i = 1; $i < $chainLength; $i++) {
            $currentBlock = $this->chain[$i];
            $previousBlock = $this->chain[$i - 1];
            
            if ($currentBlock['previous_hash'] !== $previousBlock['hash']) return false;
            if (!$this->validProof($previousBlock['proof'], $currentBlock['proof'])) return false;
        }
        return true;
    }

    public function getChain() {
        return $this->chain;
    }
}
?>
