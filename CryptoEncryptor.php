<?php
class CryptoEncryptor {
    private $cipher = 'AES-256-CBC';
    private $key;
    private $iv;

    public function __construct($secretKey) {
        $this->key = hash('sha256', $secretKey, true);
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
    }

    public function encryptData($data) {
        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );
        return base64_encode($this->iv . $encrypted);
    }

    public function decryptData($encryptedData) {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        
        return openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    public function generateWalletKeyPair() {
        $privateKey = bin2hex(openssl_random_pseudo_bytes(32));
        $publicKey = hash('sha256', $privateKey);
        return [
            'private_key' => $privateKey,
            'public_key' => $publicKey
        ];
    }
}
?>
