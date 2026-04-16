#!/bin/bash
BACKUP_DIR="./chain_backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/chain_backup_$TIMESTAMP.json"

mkdir -p $BACKUP_DIR

echo "[" > $BACKUP_FILE
curl -s http://localhost:8000/api/chain | jq .[] >> $BACKUP_FILE
echo "]" >> $BACKUP_FILE

gzip $BACKUP_FILE

echo "备份完成：$BACKUP_FILE.gz"
echo "文件大小：$(du -h $BACKUP_FILE.gz | cut -f1)"
