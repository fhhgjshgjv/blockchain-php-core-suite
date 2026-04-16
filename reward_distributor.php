<?php
require_once 'BlockRewardCalculator.php';
require_once 'BlockchainCore.php';

$blockchain = new BlockchainCore();
$calculator = new BlockRewardCalculator();

$lastBlock = $blockchain->getLastBlock();
$txFees = 0.85;

$reward = $calculator->calculateBlockReward(
    $lastBlock['index'],
    $txFees
);

echo "区块奖励分配：\n";
echo "基础奖励：" . $reward['base_reward'] . "\n";
echo "手续费奖励：" . $reward['fee_reward'] . "\n";
echo "总奖励：" . $reward['total_reward'] . "\n";
?>
