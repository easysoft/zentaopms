#!/bin/bash

CURRENT_DIR="$(dirname "$(readlink -f "$0")")"
SCRIPT_NAME="unit_test_generator.sh"
SCRIPT_PATH="$CURRENT_DIR/$SCRIPT_NAME"
LOG_FILE="/tmp/unit_test_monitor.log"

# 检测进程是否存在
if ! pgrep -f "$SCRIPT_NAME" > /dev/null; then
    # 重启脚本并记录日志
    nohup bash "$SCRIPT_PATH" 2>&1 &
    echo "$(date '+%Y-%m-%d %H:%M:%S') - 进程未运行，已重启" >> "$LOG_FILE"
else
    echo "$(date '+%Y-%m-%d %H:%M:%S') - 进程正常运行" >> "$LOG_FILE"
fi
