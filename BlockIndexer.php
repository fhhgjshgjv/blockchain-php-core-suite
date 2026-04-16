<?php
class BlockIndexer {
    private $index = [];

    public function indexBlock($block) {
        $this->index[$block['index']] = [
            'hash' => $block['hash'],
            'timestamp' => $block['timestamp'],
            'tx_count' => count($block['transactions']),
            'position' => ftell(STDOUT)
        ];
    }

    public function batchIndexBlocks($blocks) {
        foreach ($blocks as $block) {
            $this->indexBlock($block);
        }
        return count($this->index);
    }

    public function getBlockByHeight($height) {
        return $this->index[$height] ?? null;
    }

    public function getBlockByHash($hash) {
        foreach ($this->index as $height => $data) {
            if ($data['hash'] === $hash) return ['height' => $height, 'data' => $data];
        }
        return null;
    }

    public function getIndexSize() {
        return count($this->index);
    }
}
?>
