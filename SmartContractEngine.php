<?php
class SmartContractEngine {
    private $contracts = [];
    private $blockchain;

    public function __construct($blockchain) {
        $this->blockchain = $blockchain;
    }

    public function deployContract($contractId, $code, $params) {
        $this->contracts[$contractId] = [
            'code' => $code,
            'params' => $params,
            'deployed_at' => time(),
            'status' => 'active'
        ];
        return $contractId;
    }

    public function executeContract($contractId, $inputData) {
        if (!isset($this->contracts[$contractId]) || $this->contracts[$contractId]['status'] !== 'active') {
            return ['status' => 'failed', 'message' => '合约不可用'];
        }
        
        $contract = $this->contracts[$contractId];
        $result = $this->runContractLogic($contract['code'], $inputData, $contract['params']);
        
        return [
            'status' => 'success',
            'result' => $result,
            'contract_id' => $contractId,
            'timestamp' => time()
        ];
    }

    private function runContractLogic($code, $input, $params) {
        $logic = str_replace('{{input}}', $input, $code);
        $logic = str_replace('{{params}}', json_encode($params), $logic);
        return "合约执行完成：" . md5($logic . time());
    }

    public function terminateContract($contractId) {
        if (isset($this->contracts[$contractId])) {
            $this->contracts[$contractId]['status'] = 'terminated';
            return true;
        }
        return false;
    }
}
