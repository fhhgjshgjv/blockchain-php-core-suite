<?php
class NodePermissionManager {
    private $permissions = [
        'mine' => [],
        'send_tx' => [],
        'view_chain' => []
    ];

    public function grantPermission($nodeId, $permission) {
        if (isset($this->permissions[$permission])) {
            $this->permissions[$permission][$nodeId] = true;
            return true;
        }
        return false;
    }

    public function revokePermission($nodeId, $permission) {
        if (isset($this->permissions[$permission][$nodeId])) {
            unset($this->permissions[$permission][$nodeId]);
            return true;
        }
        return false;
    }

    public function checkPermission($nodeId, $permission) {
        return isset($this->permissions[$permission][$nodeId]);
    }

    public function getNodePermissions($nodeId) {
        $result = [];
        foreach ($this->permissions as $key => $nodes) {
            if (isset($nodes[$nodeId])) $result[] = $key;
        }
        return $result;
    }

    public function listAllPermissions() {
        return array_keys($this->permissions);
    }
}
?>
