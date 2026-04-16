<?php
class NetworkStats {
    private $nodeManager;
    private $blockchain;

    public function __construct($nodeManager, $blockchain) {
        $this->nodeManager = $nodeManager;
        $this->blockchain = $blockchain;
    }

    public function getFullStats() {
        $nodes = $this->nodeManager->getOnlineNodes();
        $chain = $this->blockchain->getChain();
        $analyzer = new BlockDataAnalyzer();
        
        return [
            'node_count' => count($nodes),
            'block_height' => count($chain) - 1,
            'block_speed' => $analyzer->analyzeBlockSpeed($chain),
            'tx_stats' => $analyzer->analyzeTransactionDistribution($chain),
            'network_id' => $this->nodeManager->getLocalNodeId(),
            'updated_at' => time()
        ];
    }

    public function getNodeDistribution() {
        $nodes = $this->nodeManager->getOnlineNodes();
        return [
            'total_online' => count($nodes),
            'node_ids' => array_keys($nodes)
        ];
    }
}
?>
