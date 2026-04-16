<?php
require_once 'ChainMonitor.php';
require_once 'BlockchainCore.php';

$blockchain = new BlockchainCore();
$monitor = new ChainMonitor($blockchain);

$health = $monitor->checkChainHealth();
$diskUsage = disk_free_space(".") / disk_total_space(".") * 100;

echo "=== 系统健康状态 ===\n";
echo "链状态：" . $health['status'] . "\n";
echo "当前区块高度：" . $health['block_height'] . "\n";
echo "磁盘剩余：" . round($diskUsage, 2) . "%\n";
echo "告警数量：" . count($health['alerts']) . "\n";

if (!empty($health['alerts'])) {
    echo "告警详情：" . implode(", ", $health['alerts']) . "\n";
}
?>
