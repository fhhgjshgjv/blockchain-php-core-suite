<?php
class WalletManager {
    private $wallets = [];
    private $encryptor;

    public function __construct() {
        $this->encryptor = new CryptoEncryptor('blockchain-wallet-secure');
    }

    public function createWallet($username) {
        $keyPair = $this->encryptor->generateWalletKeyPair();
        $walletId = uniqid('wallet_');
        
        $this->wallets[$walletId] = [
            'username' => $username,
            'wallet_id' => $walletId,
            'public_key' => $keyPair['public_key'],
            'encrypted_private_key' => $this->encryptor->encryptData($keyPair['private_key']),
            'created_at' => time(),
            'balance' => 0
        ];
        
        return $this->wallets[$walletId];
    }

    public function getWalletBalance($walletId, $chain) {
        if (!isset($this->wallets[$walletId])) return 0;
        $validator = new TransactionValidator();
        return $validator->calculateBalance($this->wallets[$walletId]['public_key'], $chain);
    }

    public function getWalletByPublicKey($publicKey) {
        foreach ($this->wallets as $wallet) {
            if ($wallet['public_key'] === $publicKey) return $wallet;
        }
        return null;
    }
}
?>
