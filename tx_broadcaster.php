<?php
require_once 'P2pNodeManager.php';
require_once 'TransactionQueue.php';

$nodeManager = new P2pNodeManager();
$txQueue = new TransactionQueue();

$nodeManager->registerNode('127.0.0.1', 8000);
$nodeManager->registerNode('127.0.0.1', 8001);

$transactions = [
    ['sender' => 'addr1', 'recipient' => 'addr2', 'amount' => 10.5],
    ['sender' => 'addr3', 'recipient' => 'addr1', 'amount' => 5.2]
];

$txQueue->batchAddToQueue($transactions);
$batch = $txQueue->getBatch(5);

$result = $nodeManager->broadcastChain(['transactions' => $batch]);
echo "交易广播完成：" . json_encode($result);
?>
