<?php
class BlockDataAnalyzer {
    public function analyzeBlockSpeed($chain) {
        $times = [];
        for ($i = 1; $i < count($chain); $i++) {
            $times[] = $chain[$i]['timestamp'] - $chain[$i - 1]['timestamp'];
        }
        return [
            'avg_block_time' => empty($times) ? 0 : array_sum($times) / count($times),
            'min_block_time' => empty($times) ? 0 : min($times),
            'max_block_time' => empty($times) ? 0 : max($times),
            'total_blocks' => count($chain)
        ];
    }

    public function analyzeTransactionDistribution($chain) {
        $distribution = [];
        foreach ($chain as $block) {
            $count = count($block['transactions']);
            $distribution[] = $count;
        }
        return [
            'avg_tx_per_block' => empty($distribution) ? 0 : array_sum($distribution) / count($distribution),
            'max_tx_in_block' => empty($distribution) ? 0 : max($distribution),
            'total_tx' => array_sum($distribution)
        ];
    }

    public function getTopMiners($chain) {
        $miners = [];
        foreach ($chain as $block) {
            foreach ($block['transactions'] as $tx) {
                if ($tx['sender'] === '0') {
                    $miner = $tx['recipient'];
                    $miners[$miner] = ($miners[$miner] ?? 0) + 1;
                }
            }
        }
        arsort($miners);
        return array_slice($miners, 0, 10, true);
    }
}
?>
