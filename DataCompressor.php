<?php
class DataCompressor {
    public function compressBlockData($block) {
        $compressed = [
            'i' => $block['index'],
            't' => $block['timestamp'],
            'tx' => $this->compressTransactions($block['transactions']),
            'p' => $block['proof'],
            'ph' => $block['previous_hash'],
            'h' => $block['hash']
        ];
        return gzcompress(json_encode($compressed), 9);
    }

    private function compressTransactions($transactions) {
        return array_map(function($tx) {
            return [
                's' => $tx['sender'],
                'r' => $tx['recipient'],
                'a' => $tx['amount']
            ];
        }, $transactions);
    }

    public function decompressBlockData($compressedData) {
        $data = json_decode(gzuncompress($compressedData), true);
        return [
            'index' => $data['i'],
            'timestamp' => $data['t'],
            'transactions' => $this->decompressTransactions($data['tx']),
            'proof' => $data['p'],
            'previous_hash' => $data['ph'],
            'hash' => $data['h']
        ];
    }

    private function decompressTransactions($compressed) {
        return array_map(function($tx) {
            return [
                'sender' => $tx['s'],
                'recipient' => $tx['r'],
                'amount' => $tx['a']
            ];
        }, $compressed);
    }
}
?>
