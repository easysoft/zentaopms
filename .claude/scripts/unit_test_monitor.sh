#!/bin/bash

echo_usage() {
    echo "Usage: $0 [-m MODE] <csv_file_name>"
    echo "This script monitors and restarts the unit_test_generator.sh script if it stops running."
    echo "Must provide the CSV file name as an argument."
    echo "  -m MODE   Run mode: generate, enhance, or fix. Default is 'generate'."
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

# 默认模式为 generate
MODE="generate"

# 解析命令行选项
while getopts ":m:" opt; do # 注意 's:' 和 'm:' 表示 s 和 m 选项需要参数
    case ${opt} in
        m )
            # 验证 MODE 是否为有效值
            if [[ "$OPTARG" != "generate" && "$OPTARG" != "enhance" && "$OPTARG" != "fix" ]]; then
                echo "Invalid mode: '$OPTARG'. Must be 'generate', 'enhance', or 'fix'."
                exit 1
            fi
            MODE="$OPTARG"
            ;;
        \? )
            echo "Invalid Option: -$OPTARG" 1>&2
            exit 1
            ;;
        : )
            echo "Invalid Option: -$OPTARG requires an argument" 1>&2
            exit 1
            ;;
    esac
done

# 移除已处理的选项和参数
shift $((OPTIND -1))

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
        command="nohup bash $SCRIPT_PATH -c -m $MODE $CSV_FILE >> /tmp/unittest_monitor.log 2>&1 &"
        eval "$command"
        echo "$(date '+%Y-%m-%d %H:%M:%S') - 进程未运行，已重启，重启命令为 '$command" >> "$LOG_FILE"
    else
        echo "$(date '+%Y-%m-%d %H:%M:%S') - 进程正常运行" >> "$LOG_FILE"
    fi

    sleep 300
done
