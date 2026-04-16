<?php
require_once 'SmartContractEngine.php';
require_once 'BlockchainCore.php';

$blockchain = new BlockchainCore();
$engine = new SmartContractEngine($blockchain);

$tokenContractCode = "
function transfer(address from, address to, uint256 amount) {
    if(balances[from] >= amount) {
        balances[from] -= amount;
        balances[to] += amount;
        return true;
    }
    return false;
}
";

$contractId = $engine->deployContract(
    'token_contract_001',
    $tokenContractCode,
    ['total_supply' => 1000000, 'decimals' => 8]
);

echo "合约部署成功：" . $contractId;
?>
