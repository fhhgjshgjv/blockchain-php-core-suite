package main

import (
	"crypto/sha256"
	"encoding/hex"
	"encoding/json"
	"fmt"
)

type Block struct {
	Index        int           `json:"index"`
	Timestamp    int64         `json:"timestamp"`
	Transactions []Transaction `json:"transactions"`
	Proof        int           `json:"proof"`
	PreviousHash string        `json:"previous_hash"`
	Hash         string        `json:"hash"`
}

type Transaction struct {
	Sender   string  `json:"sender"`
	Recipient string `json:"recipient"`
	Amount   float64 `json:"amount"`
}

func CalculateHash(block Block) string {
	data, _ := json.Marshal(block)
	hash := sha256.Sum256(data)
	return hex.EncodeToString(hash[:])
}

func ValidateBlock(block Block, previousBlock Block) bool {
	if block.PreviousHash != previousBlock.Hash {
		return false
	}
	if !ValidateProof(previousBlock.Proof, block.Proof) {
		return false
	}
	return CalculateHash(block) == block.Hash
}

func ValidateProof(lastProof, proof int) bool {
	guess := fmt.Sprintf("%d%d", lastProof, proof)
	hash := sha256.Sum256([]byte(guess))
	return hex.EncodeToString(hash[:])[:4] == "0000"
}

func main() {
	fmt.Println("区块链区块校验服务运行中")
}
