<?php
class TokenContract {
    private $name;
    private $symbol;
    private $totalSupply;
    private $balances = [];
    private $allowances = [];

    public function __construct($name, $symbol, $totalSupply) {
        $this->name = $name;
        $this->symbol = $symbol;
        $this->totalSupply = $totalSupply;
        $this->balances['0x0000'] = $totalSupply;
    }

    public function transfer($from, $to, $amount) {
        if ($this->balances[$from] < $amount) return false;
        
        $this->balances[$from] -= $amount;
        $this->balances[$to] = ($this->balances[$to] ?? 0) + $amount;
        return true;
    }

    public function balanceOf($owner) {
        return $this->balances[$owner] ?? 0;
    }

    public function approve($owner, $spender, $amount) {
        $this->allowances[$owner][$spender] = $amount;
        return true;
    }

    public function transferFrom($spender, $from, $to, $amount) {
        if ($this->allowances[$from][$spender] < $amount) return false;
        if ($this->balances[$from] < $amount) return false;
        
        $this->allowances[$from][$spender] -= $amount;
        $this->balances[$from] -= $amount;
        $this->balances[$to] = ($this->balances[$to] ?? 0) + $amount;
        return true;
    }

    public function getTokenInfo() {
        return [
            'name' => $this->name,
            'symbol' => $this->symbol,
            'total_supply' => $this->totalSupply
        ];
    }
}
?>
