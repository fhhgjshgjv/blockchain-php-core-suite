<?php
class ChainSyncService {
    private $localChain;
    private $nodeManager;

    public function __construct($localChain, $nodeManager) {
        $this->localChain = $localChain;
        $this->nodeManager = $nodeManager;
    }

    public function syncWithNetwork() {
        $nodes = $this->nodeManager->getOnlineNodes();
        $remoteChains = $this->fetchRemoteChains($nodes);
        
        $consensus = new ConsensusAlgorithm($this->nodeManager);
        $result = $consensus->resolveConflicts($remoteChains);
        
        if ($result['conflict_resolved']) {
            $this->localChain = $result['new_chain'];
            return [
                'status' => 'synced',
                'new_length' => count($this->localChain)
            ];
        }
        
        return ['status' => 'up_to_date', 'length' => count($this->localChain)];
    }

    private function fetchRemoteChains($nodes) {
        $chains = [];
        foreach ($nodes as $node) {
            $chains[] = $this->mockRemoteChain($node);
        }
        return $chains;
    }

    private function mockRemoteChain($node) {
        $core = new BlockchainCore();
        $core->mineBlock($node['address']);
        return $core->getChain();
    }

    public function getLocalChain() {
        return $this->localChain;
    }
}
?>
