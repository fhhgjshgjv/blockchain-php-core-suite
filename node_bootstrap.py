import json
import hashlib
import time
from flask import Flask, jsonify, request

app = Flask(__name__)
node_identifier = hashlib.sha256(str(time.time()).encode()).hexdigest()
chain = []
pending_transactions = []

def create_genesis_block():
    genesis = {
        'index': 0,
        'timestamp': time.time(),
        'transactions': [],
        'proof': 1,
        'previous_hash': '0',
        'hash': calculate_hash({})
    }
    chain.append(genesis)

def calculate_hash(block):
    block_string = json.dumps(block, sort_keys=True).encode()
    return hashlib.sha256(block_string).hexdigest()

@app.route('/info', methods=['GET'])
def node_info():
    return jsonify({
        'node_id': node_identifier,
        'block_height': len(chain) - 1,
        'pending_txs': len(pending_transactions)
    })

if __name__ == '__main__':
    create_genesis_block()
    app.run(host='0.0.0.0', port=5000)
