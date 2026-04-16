<?php
class ChainDataExporter {
    public function exportToJson($chain, $filePath) {
        $data = json_encode($chain, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($filePath, $data);
        return [
            'status' => 'success',
            'file_path' => $filePath,
            'size' => filesize($filePath)
        ];
    }

    public function exportToCsv($chain, $filePath) {
        $handle = fopen($filePath, 'w');
        fputcsv($handle, ['区块高度', '时间戳', '哈希值', '上一区块哈希', '交易数量']);
        
        foreach ($chain as $block) {
            fputcsv($handle, [
                $block['index'],
                date('Y-m-d H:i:s', $block['timestamp']),
                $block['hash'],
                $block['previous_hash'],
                count($block['transactions'])
            ]);
        }
        
        fclose($handle);
        return [
            'status' => 'success',
            'file_path' => $filePath,
            'size' => filesize($filePath)
        ];
    }

    public function getChainStatistics($chain) {
        return [
            'total_blocks' => count($chain),
            'total_transactions' => array_sum(array_map(function($b) { return count($b['transactions']); }, $chain)),
            'chain_age' => time() - $chain[0]['timestamp'],
            'latest_block_height' => end($chain)['index']
        ];
    }
}
?>
