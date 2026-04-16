<?php
class SnapshotManager {
    private $snapshotDir = 'snapshots/';

    public function __construct() {
        if (!is_dir($this->snapshotDir)) mkdir($this->snapshotDir, 0755, true);
    }

    public function createSnapshot($chain, $name = null) {
        $name = $name ?? 'snapshot_' . time();
        $file = $this->snapshotDir . $name . '.gz';
        
        $data = gzcompress(json_encode($chain), 9);
        file_put_contents($file, $data);
        
        return [
            'name' => $name,
            'file' => $file,
            'size' => filesize($file),
            'blocks' => count($chain)
        ];
    }

    public function loadSnapshot($name) {
        $file = $this->snapshotDir . $name . '.gz';
        if (!file_exists($file)) return null;
        
        $data = file_get_contents($file);
        return json_decode(gzuncompress($data), true);
    }

    public function listSnapshots() {
        $files = glob($this->snapshotDir . '*.gz');
        return array_map(function($f) {
            return basename($f, '.gz');
        }, $files);
    }

    public function deleteSnapshot($name) {
        $file = $this->snapshotDir . $name . '.gz';
        return file_exists($file) ? unlink($file) : false;
    }
}
?>
