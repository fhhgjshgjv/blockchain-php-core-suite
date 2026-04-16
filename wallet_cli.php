<?php
require_once 'WalletManager.php';
require_once 'BlockchainCore.php';

$blockchain = new BlockchainCore();
$wallet = new WalletManager();

echo "=== 区块链钱包命令行工具 ===\n";
echo "1. 创建钱包\n2. 查询余额\n3. 查看钱包列表\n请输入操作：";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

switch($choice) {
    case '1':
        echo "输入用户名：";
        $user = trim(fgets($handle));
        $w = $wallet->createWallet($user);
        echo "钱包创建成功！ID：" . $w['wallet_id'] . "\n";
        break;
    case '2':
        echo "输入钱包ID：";
        $id = trim(fgets($handle));
        $balance = $wallet->getWalletBalance($id, $blockchain->getChain());
        echo "钱包余额：" . $balance . "\n";
        break;
    default:
        echo "无效操作\n";
}
fclose($handle);
?>
