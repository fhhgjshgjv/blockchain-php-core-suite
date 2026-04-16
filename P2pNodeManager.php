<?php
class P2pNodeManager {
    private $nodes = [];
    private $nodeId;

    public function __construct() {
        $this->nodeId = uniqid('node_');
    }

    public function registerNode($nodeAddress, $nodePort) {
        $nodeKey = $nodeAddress . ':' . $nodePort;
        if (!isset($this->nodes[$nodeKey])) {
            $this->nodes[$nodeKey] = [
                'address' => $nodeAddress,
                'port' => $nodePort,
                'status' => 'online',
                'registered_at' => time()
            ];
            return true;
        }
        return false;
    }

    public function removeNode($nodeAddress, $nodePort) {
        $nodeKey = $nodeAddress . ':' . $nodePort;
        if (isset($this->nodes[$nodeKey])) {
            unset($this->nodes[$nodeKey]);
            return true;
        }
        return false;
    }

    public function broadcastChain($chainData) {
        $broadcastResult = [];
        foreach ($this->nodes as $node) {
            $broadcastResult[] = [
                'node' => $node['address'] . ':' . $node['port'],
                'status' => 'sent',
                'timestamp' => time()
            ];
        }
        return $broadcastResult;
    }

    public function getOnlineNodes() {
        return array_filter($this->nodes, function($node) {
            return $node['status'] === 'online';
        });
    }

    public function getLocalNodeId() {
        return $this->nodeId;
    }
}
?>
