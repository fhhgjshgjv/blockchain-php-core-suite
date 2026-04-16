<?php
header("Content-Type: text/html");
?>
<!DOCTYPE html>
<html>
<head>
    <title>区块链API文档</title>
    <style>body{font-family: Arial;margin:20px}pre{background:#f5f5f5;padding:10px}</style>
</head>
<body>
    <h1>区块链核心API接口</h1>
    <h3>1. 获取整条链</h3>
    <pre>GET /api/chain</pre>
    
    <h3>2. 创建交易</h3>
    <pre>POST /api/transactions/new
Body: {"sender":"","recipient":"","amount":0}</pre>
    
    <h3>3. 挖矿</h3>
    <pre>GET /api/mine?miner=地址</pre>
    
    <h3>4. 创建钱包</h3>
    <pre>GET /api/wallet/create?username=名称</pre>
</body>
</html>
