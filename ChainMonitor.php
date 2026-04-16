<?php
class ChainMonitor {
    private $blockchain;
    private $alerts = [];

    public function __construct($blockchain) {
        $this->blockchain = $blockchain;
    }

    public function checkChainHealth() {
        $isValid = $this->blockchain->isChainValid();
        $lastBlock = $this->blockchain->getLastBlock();
        $timeDiff = time() - $lastBlock['timestamp'];
        
        if (!$isValid) $this->alerts[] = '区块链数据校验失败';
        if ($timeDiff > 3600) $this->alerts[] = '长时间未生成新区块';
        
        return [
            'status' => empty($this->alerts) ? 'healthy' : 'warning',
            'last_block_time' => date('Y-m-d H:i:s', $lastBlock['timestamp']),
            'alerts' => $this->alerts,
            'block_height' => $lastBlock['index']
        ];
    }

    public function monitorAddress($address) {
        $chain = $this->blockchain->getChain();
        $txCount = 0;
        foreach ($chain as $block) {
            foreach ($block['transactions'] as $tx) {
                if ($tx['sender'] === $address || $tx['recipient'] === $address) $txCount++;
            }
        }
        return ['address' => $address, 'transaction_count' => $txCount];
    }
}
?>
