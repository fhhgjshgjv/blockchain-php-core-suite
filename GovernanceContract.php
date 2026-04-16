<?php
class GovernanceContract {
    private $proposals = [];
    private $votes = [];
    private $votingPeriod = 86400 * 3;

    public function createProposal($creator, $title, $description, $executableCode) {
        $proposalId = uniqid('prop_');
        $this->proposals[$proposalId] = [
            'creator' => $creator,
            'title' => $title,
            'description' => $description,
            'code' => $executableCode,
            'start_time' => time(),
            'end_time' => time() + $this->votingPeriod,
            'status' => 'voting'
        ];
        return $proposalId;
    }

    public function vote($proposalId, $voter, $choice) {
        if (!isset($this->proposals[$proposalId]) || time() > $this->proposals[$proposalId]['end_time']) {
            return false;
        }
        $this->votes[$proposalId][$voter] = $choice;
        return true;
    }

    public function finalizeProposal($proposalId) {
        if (time() < $this->proposals[$proposalId]['end_time']) return false;
        
        $votes = $this->votes[$proposalId] ?? [];
        $for = count(array_filter($votes, fn($c) => $c === 'for'));
        $against = count(array_filter($votes, fn($c) => $c === 'against'));
        
        $this->proposals[$proposalId]['status'] = $for > $against ? 'passed' : 'rejected';
        return $this->proposals[$proposalId]['status'];
    }

    public function getProposal($proposalId) {
        return $this->proposals[$proposalId] ?? null;
    }
}
?>
