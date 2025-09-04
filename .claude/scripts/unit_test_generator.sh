#!/bin/bash

# 自动生成单元测试脚本
# 作者: Auto-generated script
# 日期: $(date +%Y-%m-%d)

# 设置变量

#CURRENT_DIR="$(dirname "$(readlink -f "$0")")"
BASE_PATH=$(readlink -f "$(dirname "${BASH_SOURCE[0]}")/../..")
GUIDE_FILE="$BASE_PATH/.claude/rules/zentao-unit-test-guide.md"
CSV_FILE="$BASE_PATH/.claude/data/open_model_no_case.csv"
LOG_FILE="$BASE_PATH/tmp/log/unit_test_generation_$(date +%Y%m%d_%H%M%S).log"
CURRENT_LINE=0
TOTAL_PROCESSED=0
TOTAL_SKIPPED=0
TOTAL_GENERATED=0

# 检查必要文件和工具
check_prerequisites() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 检查前置条件..." | tee -a "$LOG_FILE"

    if [ ! -f "$CSV_FILE" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] CSV文件不存在: $CSV_FILE" | tee -a "$LOG_FILE"
        exit 1
    fi

    if ! command -v claude &> /dev/null; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] claude命令未找到，请确保已安装Claude CLI" | tee -a "$LOG_FILE"
        exit 1
    fi

    if [ ! -d "$BASE_PATH" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 基础路径不存在: $BASE_PATH" | tee -a "$LOG_FILE"
        exit 1
    fi

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 前置条件检查完成" | tee -a "$LOG_FILE"
}

# 处理单个方法的单元测试生成
process_method() {
    local module="$1"
    local class="$2"
    local method="$3"

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 处理第 $CURRENT_LINE 行: 模块=$module, 类=$class, 方法=$method" | tee -a "$LOG_FILE"

    # 构建测试文件路径（方法名转为小写）
    local method_lower=$(echo "$method" | tr '[:upper:]' '[:lower:]')
    local test_file_path="$BASE_PATH/module/$module/test/$class/$method_lower.php"

    # 检查测试文件是否存在
    if [ -f "$test_file_path" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件已存在，跳过: $test_file_path" | tee -a "$LOG_FILE"
        ((TOTAL_SKIPPED++))
        return 0
    fi

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件不存在，开始生成: $test_file_path" | tee -a "$LOG_FILE"

    # 创建测试目录（如果不存在）
    local test_dir="$BASE_PATH/module/$module/test/$class"
    if [ ! -d "$test_dir" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 创建测试目录: $test_dir" | tee -a "$LOG_FILE"
        mkdir -p "$test_dir"
    fi

    # 构建Claude命令
    local claude_prompt="根据$GUIDE_FILE文档的内容为$module模块的$class.php文件中的$method方法生成单元测试脚本。"

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 执行Claude命令: claude -p \"$claude_prompt\" --dangerously-skip-permissions" | tee -a "$LOG_FILE"

    # 执行Claude命令并捕获输出
    local claude_output
    if claude_output=$(claude -p "$claude_prompt" --dangerously-skip-permissions 2>&1); then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [SUCCESS] Claude命令执行成功" | tee -a "$LOG_FILE"

        # 将Claude输出保存到测试文件
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件已生成: $test_file_path" | tee -a "$LOG_FILE"

        # 记录详细的Claude输出到日志
        echo "$(date '+%Y-%m-%d %H:%M:%S') [DEBUG] Claude输出内容:" | tee -a "$LOG_FILE"
        echo "--- 开始输出 ---" | tee -a "$LOG_FILE"
        echo "$claude_output" | tee -a "$LOG_FILE"
        echo "--- 结束输出 ---" | tee -a "$LOG_FILE"

        ((TOTAL_GENERATED++))
    else
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] Claude命令执行失败" | tee -a "$LOG_FILE"
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 错误信息: $claude_output" | tee -a "$LOG_FILE"
        return 1
    fi

    return 0
}

# 主处理函数
main() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] ========== 开始执行单元测试生成脚本 ==========" | tee -a "$LOG_FILE"

    # 检查前置条件
    check_prerequisites

    # 读取CSV文件（跳过第一行标题）
    local line_count=$(wc -l < "$CSV_FILE")
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] CSV文件总行数: $line_count (包含标题行)" | tee -a "$LOG_FILE"

    # 使用while循环读取CSV文件
    while IFS=',' read -r module class method || [ -n "$module" ]; do
        ((CURRENT_LINE++))

        # 跳过第一行标题
        if [ $CURRENT_LINE -eq 1 ]; then
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
        else
            echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 第 $CURRENT_LINE 行处理失败" | tee -a "$LOG_FILE"
        fi

        ((TOTAL_PROCESSED++))

        # 进度提示
        if [ $((TOTAL_PROCESSED % 10)) -eq 0 ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [PROGRESS] 已处理 $TOTAL_PROCESSED 个方法" | tee -a "$LOG_FILE"
        fi

        # 避免频繁调用Claude，添加短暂延迟
        sleep 1

    done < "$CSV_FILE"

    # 输出统计信息
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] ========== 执行完成 ==========" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 总处理数量: $TOTAL_PROCESSED" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 跳过数量: $TOTAL_SKIPPED" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 生成数量: $TOTAL_GENERATED" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 日志文件: $LOG_FILE" | tee -a "$LOG_FILE"
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
