<?php
require_once 'ConsensusAlgorithm.php';
require_once 'P2pNodeManager.php';

$nodeManager = new P2pNodeManager();
$consensus = new ConsensusAlgorithm($nodeManager);

$nodeManager->registerNode('192.168.1.100', 8000);
$nodeManager->registerNode('192.168.1.101', 8000);

function mockForkedChains() {
    $chain1 = (new BlockchainCore())->getChain();
    $chain2 = (new BlockchainCore())->getChain();
    $chain2[] = ['index' => 1, 'hash' => 'fake_hash', 'previous_hash' => 'invalid'];
    return [$chain1, $chain2];
}

$chains = mockForkedChains();
$result = $consensus->resolveConflicts($chains);

echo "分叉检测结果：" . ($result['conflict_resolved'] ? "已修复" : "无分叉");
?>
