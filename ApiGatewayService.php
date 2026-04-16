<?php
header("Content-Type: application/json");
class ApiGatewayService {
    private $blockchain;
    private $walletManager;

    public function __construct() {
        $this->blockchain = new BlockchainCore();
        $this->walletManager = new WalletManager();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'info';
        switch ($action) {
            case 'chain': echo json_encode($this->blockchain->getChain()); break;
            case 'mine': $this->mine(); break;
            case 'transactions/new': $this->newTransaction(); break;
            case 'wallet/create': $this->createWallet(); break;
            default: echo json_encode(['status' => 'invalid action']);
        }
    }

    private function mine() {
        $miner = $_GET['miner'] ?? 'default_miner';
        $block = $this->blockchain->mineBlock($miner);
        echo json_encode($block);
    }

    private function newTransaction() {
        $data = json_decode(file_get_contents('php://input'), true);
        $index = $this->blockchain->addTransaction($data['sender'], $data['recipient'], $data['amount']);
        echo json_encode(['status' => 'success', 'block_index' => $index]);
    }

    private function createWallet() {
        $wallet = $this->walletManager->createWallet($_GET['username']);
        echo json_encode($wallet);
    }
}

$api = new ApiGatewayService();
$api->handleRequest();
?>
