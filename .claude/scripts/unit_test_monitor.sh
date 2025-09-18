#!/bin/bash

echo_usage() {
    echo "Usage: $0 <csv_file_name>"
    echo "This script monitors and restarts the unit_test_generator.sh script if it stops running."
    echo "Must provide the CSV file name as an argument."
    exit 1
}

# 检查参数数量
if [ "$#" -eq 0 ]; then
    echo_usage
fi

# 检查是否提供了 CSV 文件名
if [ -z "$1" ]; then
    echo_usage
fi

CSV_FILE="$1"
CURRENT_DIR="$(dirname "$(readlink -f "$0")")"
SCRIPT_NAME="unit_test_generator.sh"
SCRIPT_PATH="$CURRENT_DIR/$SCRIPT_NAME"
LOG_FILE="/tmp/unit_test_monitor.log"

# 每 5 分钟运行一次
while true; do
    # 检测进程是否存在
    if ! pgrep -f "$SCRIPT_NAME" > /dev/null; then
        # 重启脚本并记录日志
        nohup bash "$SCRIPT_PATH" "$CSV_FILE" -c >> /tmp/unittest_monitor.log 2>&1 &
        echo "$(date '+%Y-%m-%d %H:%M:%S') - 进程未运行，已重启" >> "$LOG_FILE"
    else
        echo "$(date '+%Y-%m-%d %H:%M:%S') - 进程正常运行" >> "$LOG_FILE"
    fi

    sleep 300
done
