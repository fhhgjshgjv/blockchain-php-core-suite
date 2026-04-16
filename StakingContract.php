<?php
class StakingContract {
    private $stakes = [];
    private $rewardRate = 0.05;
    private $minStake = 100;

    public function stake($address, $amount) {
        if ($amount < $this->minStake) return false;
        
        $this->stakes[$address] = [
            'amount' => $amount,
            'start_time' => time(),
            'claimed_rewards' => 0
        ];
        return true;
    }

    public function unstake($address) {
        if (!isset($this->stakes[$address])) return false;
        
        $rewards = $this->calculateRewards($address);
        $total = $this->stakes[$address]['amount'] + $rewards;
        unset($this->stakes[$address]);
        return $total;
    }

    public function calculateRewards($address) {
        if (!isset($this->stakes[$address])) return 0;
        
        $duration = time() - $this->stakes[$address]['start_time'];
        $years = $duration / (365 * 24 * 60 * 60);
        $rewards = $this->stakes[$address]['amount'] * $this->rewardRate * $years;
        
        return round($rewards, 8);
    }

    public function getStakeInfo($address) {
        return $this->stakes[$address] ?? null;
    }
}
?>
