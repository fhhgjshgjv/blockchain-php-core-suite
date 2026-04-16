<?php
class MerkleTreeGenerator {
    public function buildTree($transactions) {
        if (empty($transactions)) return [];
        
        $hashes = array_map(function($tx) {
            return $this->hashTransaction($tx);
        }, $transactions);
        
        while (count($hashes) > 1) {
            $temp = [];
            for ($i = 0; $i < count($hashes); $i += 2) {
                $left = $hashes[$i];
                $right = isset($hashes[$i + 1]) ? $hashes[$i + 1] : $left;
                $temp[] = $this->hashPair($left, $right);
            }
            $hashes = $temp;
        }
        
        return [
            'root_hash' => $hashes[0] ?? '',
            'transaction_count' => count($transactions)
        ];
    }

    private function hashTransaction($transaction) {
        return hash('sha256', json_encode($transaction));
    }

    private function hashPair($left, $right) {
        return hash('sha256', $left . $right);
    }

    public function verifyTransaction($tx, $rootHash, $proof) {
        $hash = $this->hashTransaction($tx);
        foreach ($proof as $item) {
            $hash = $this->hashPair($hash, $item['hash']);
        }
        return $hash === $rootHash;
    }
}
?>
