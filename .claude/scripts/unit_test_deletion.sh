#!/bin/bash

# 自动删除单元测试脚本
# 作者: Auto-generated script
# 日期: $(date +%Y-%m-%d)

echo_usage() {
    echo "Usage: $0  <csv_file_name>"
    echo "This script deletes fail unit test scripts based on the provided CSV file."
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

# 默认从第2行开始（跳过标题行）
START_LINE=2

# 设置变量
BASE_PATH=$(readlink -f "$(dirname "${BASH_SOURCE[0]}")/../..")
CSV_FILE="$BASE_PATH/.claude/data/$1"
NEW_FILE="$BASE_PATH/.claude/data/cases_to_generate.csv"
LOG_FILE="$BASE_PATH/tmp/log/unit_test_deletion_$(date +%Y%m%d_%H%M%S).log"

CURRENT_LINE=0
TOTAL_PROCESSED=0
TOTAL_SKIPPED=0
TOTAL_DELETED=0

# 检查必要文件和工具
check_prerequisites() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 检查前置条件..." | tee -a "$LOG_FILE"

    if [ ! -f "$CSV_FILE" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] CSV文件不存在: $CSV_FILE" | tee -a "$LOG_FILE"
        exit 1
    fi

    if [ ! -d "$BASE_PATH" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 基础路径不存在: $BASE_PATH" | tee -a "$LOG_FILE"
        exit 1
    fi

    if [ -f "$NEW_FILE" ]; then
        echo "模块,类,函数名" > "$NEW_FILE"
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 已清空目标文件: $NEW_FILE" | tee -a "$LOG_FILE"
    fi

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 前置条件检查完成" | tee -a "$LOG_FILE"
}

# 删除单个方法的单元测试脚本
process_method() {
    local module="$1"
    local class="$2"
    local method="$3"

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 处理第 $CURRENT_LINE 行: 模块=$module, 类=$class, 方法=$method" | tee -a "$LOG_FILE"

    # 构建测试文件路径（方法名转为小写）
    local method_lower=$(echo "$method" | tr '[:upper:]' '[:lower:]')
    local test_file="$BASE_PATH/module/$module/test/$class/$method_lower.php"

    # 删除测试文件
    if [ -f "$test_file" ]; then
        # 获取该文件所有有改动的提交（按时间倒序）
        local commits=$(git log --oneline --follow -- "$test_file" | awk '{print $1}')
        local all_generated=true

        # 如果每一个提交信息都包含Claude Code则该文件是自动生成的。
        for commit in $commits; do
            local commit_msg=$(git log -1 --format=%B "$commit")
            if [[ "$commit_msg" != *"Claude Code"* ]]; then
                all_generated=false
                break
            fi
        done

        # 删除文件或跳过
        if [ "$all_generated" = true ]; then
            rm -f "$test_file"
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 已删除测试文件: $test_file" | tee -a "$LOG_FILE"

            if [ "$method" != "__construct" ]; then
                echo "$module,$class,$method" >> "$NEW_FILE"
                echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 已记录$module,$class,$method到目标文件: $NEW_FILE" | tee -a "$LOG_FILE"
            fi

            ((TOTAL_DELETED++))
        else
            echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 测试文件包含非自动生成的提交，跳过删除: $test_file" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
        fi
    else
        echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 测试文件不存在，跳过删除: $test_file" | tee -a "$LOG_FILE"
    fi

    return 0
}

# 主处理函数
main() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] ========== 开始执行单元测试删除脚本 ==========" | tee -a "$LOG_FILE"

    # 检查前置条件
    check_prerequisites

    # 读取CSV文件（跳过第一行标题）
    local line_count=$(wc -l < "$CSV_FILE")
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] CSV文件总行数: $line_count (包含标题行)" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 从第 $START_LINE 行开始处理 (跳过前面的数据行和标题行)." | tee -a "$LOG_FILE"

    # 使用while循环读取CSV文件
    while IFS=',' read -r module class method || [ -n "$module" ]; do
        ((CURRENT_LINE++))

        # 跳过标题行
        if [ $CURRENT_LINE -eq 1 ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 跳过标题行" | tee -a "$LOG_FILE"
            continue
        fi

        # 跳过指定行数之前的数据行和标题行
        if [ $CURRENT_LINE -lt $START_LINE ]; then
             echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 跳过第 $CURRENT_LINE 行 (在起始行 $START_LINE 之前)." | tee -a "$LOG_FILE"
             continue
        fi

        # module只能包含小写字母
        if ! [[ "$module" =~ ^[a-z]+$ ]]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 第 $CURRENT_LINE 行模块名格式不正确，跳过: $module" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
            continue
        fi

        # class只能是model、tao、zen其中之一
        if ! [[ "$class" =~ ^(model|tao|zen)$ ]]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 第 $CURRENT_LINE 行类名格式不正确，跳过: $class" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
            continue
        fi

        # method只能包含字母和数字且必须以下划线或小写字母开头
        if ! [[ "$method" =~ ^[a-z_][a-zA-Z0-9_]*$ ]]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 第 $CURRENT_LINE 行方法名格式不正确，跳过: $method" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
            continue
        fi

        # 清理字段中的回车符和空白字符
        module=$(echo "$module" | tr -d '\r\n' | xargs)
        class=$(echo "$class" | tr -d '\r\n' | xargs)
        method=$(echo "$method" | tr -d '\r\n' | xargs)

        # 跳过空行
        if [ -z "$module" ] || [ -z "$class" ] || [ -z "$method" ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 第 $CURRENT_LINE 行数据不完整，跳过: 模块=$module, 类=$class, 方法=$method" | tee -a "$LOG_FILE"
            continue
        fi

        # 处理当前方法
        if process_method "$module" "$class" "$method"; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [SUCCESS] 第 $CURRENT_LINE 行处理完成" | tee -a "$LOG_FILE"
        fi

        ((TOTAL_PROCESSED++))

        # 进度提示
        if [ $((TOTAL_PROCESSED % 10)) -eq 0 ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [PROGRESS] 已处理 $TOTAL_PROCESSED 个方法" | tee -a "$LOG_FILE"
        fi

    done < "$CSV_FILE"

    # 输出统计信息
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] ========== 执行完成 ==========" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 总处理数量: $TOTAL_PROCESSED" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 跳过数量: $TOTAL_SKIPPED" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 成功删除数量: $TOTAL_DELETED" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 日志文件: $LOG_FILE" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 目标文件: $NEW_FILE" | tee -a "$LOG_FILE"
}

# 信号处理函数
cleanup() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 接收到中断信号，正在清理..." | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 中断前已处理: $TOTAL_PROCESSED 个方法" | tee -a "$LOG_FILE"
    exit 130
}

# 注册信号处理
trap cleanup INT TERM

# 执行主函数
main

echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 脚本执行完成！" | tee -a "$LOG_FILE"
