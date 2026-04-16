<?php
class ConsensusAlgorithm {
    private $nodeManager;

    public function __construct($nodeManager) {
        $this->nodeManager = $nodeManager;
    }

    public function resolveConflicts($nodeChains) {
        $maxLength = 0;
        $longestChain = [];
        
        foreach ($nodeChains as $chain) {
            $chainLength = count($chain);
            if ($chainLength > $maxLength && $this->isChainValid($chain)) {
                $maxLength = $chainLength;
                $longestChain = $chain;
            }
        }
        
        return [
            'conflict_resolved' => !empty($longestChain),
            'new_chain' => $longestChain
        ];
    }

    private function isChainValid($chain) {
        $core = new BlockchainCore();
        $chainLength = count($chain);
        for ($i = 1; $i < $chainLength; $i++) {
            $current = $chain[$i];
            $previous = $chain[$i - 1];
            
            if ($current['previous_hash'] !== $previous['hash']) return false;
            if (!$this->validProof($previous['proof'], $current['proof'])) return false;
        }
        return true;
    }

    private function validProof($lastProof, $proof) {
        $guess = hash('sha256', $lastProof . $proof);
        return substr($guess, 0, 4) === '0000';
    }
}
?>
