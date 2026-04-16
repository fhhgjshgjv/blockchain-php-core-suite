<?php
class BlockRewardCalculator {
    private $baseReward = 10;
    private $halvingInterval = 210000;
    private $txFeeRatio = 0.05;

    public function calculateBlockReward($blockHeight, $transactionFees) {
        $halvings = floor($blockHeight / $this->halvingInterval);
        $reward = $this->baseReward / (pow(2, $halvings));
        
        $feeReward = $transactionFees * $this->txFeeRatio;
        $totalReward = $reward + $feeReward;
        
        return [
            'base_reward' => round($reward, 8),
            'fee_reward' => round($feeReward, 8),
            'total_reward' => round($totalReward, 8),
            'halving_count' => $halvings
        ];
    }

    public function calculateTotalMined($blockHeight) {
        $total = 0;
        $currentReward = $this->baseReward;
        
        for ($i = 0; $i < $blockHeight; $i++) {
            if ($i % $this->halvingInterval === 0 && $i > 0) {
                $currentReward /= 2;
            }
            $total += $currentReward;
        }
        return round($total, 8);
    }
}
?>
