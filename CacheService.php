<?php
class CacheService {
    private $cache = [];
    private $ttl = 300;

    public function set($key, $value) {
        $this->cache[$key] = [
            'value' => $value,
            'expires' => time() + $this->ttl
        ];
    }

    public function get($key) {
        if (!isset($this->cache[$key])) return null;
        if ($this->cache[$key]['expires'] < time()) {
            unset($this->cache[$key]);
            return null;
        }
        return $this->cache[$key]['value'];
    }

    public function delete($key) {
        unset($this->cache[$key]);
    }

    public function clear() {
        $this->cache = [];
    }

    public function has($key) {
        return isset($this->cache[$key]) && $this->cache[$key]['expires'] >= time();
    }
}
?>
