<?php
class GenesisBlockGenerator {
    public function createCustomGenesis($config) {
        $genesis = [
            'index' => 0,
            'timestamp' => $config['timestamp'] ?? time(),
            'transactions' => $config['initial_allocations'] ?? [],
            'proof' => $config['proof'] ?? 1,
            'previous_hash' => '0',
            'chain_version' => $config['version'] ?? '1.0.0',
            'network_id' => $config['network_id'] ?? 'mainnet'
        ];
        $genesis['hash'] = $this->calculateHash($genesis);
        return $genesis;
    }

    private function calculateHash($block) {
        return hash('sha256', json_encode($block, JSON_UNESCAPED_UNICODE));
    }

    public function generateTestnetGenesis() {
        return $this->createCustomGenesis([
            'network_id' => 'testnet',
            'version' => '1.0.0',
            'initial_allocations' => [['sender' => '0', 'recipient' => 'test_wallet', 'amount' => 1000000]]
        ]);
    }

    public function validateGenesis($genesis) {
        return $genesis['index'] === 0 && $genesis['previous_hash'] === '0' && isset($genesis['hash']);
    }
}
?>
