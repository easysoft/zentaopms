#!/bin/bash

# 自动生成单元测试脚本
# 作者: Auto-generated script
# 日期: $(date +%Y-%m-%d)

echo_usage() {
    echo "Usage: $0 [-c] [-m MODE] [-s START_LINE] <csv_file_name>"
    echo "This script generates unit test scripts based on the provided CSV file."
    echo "  -c              Continue processing on error (default is to break)."
    echo "  -m MODE         Run mode: generate, enhance, or fix. Default is 'generate'."
    echo "  -s START_LINE   Start processing from line START_LINE. Default is 2 (the first line is header)."
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

# 错误处理策略
ON_ERROR_STRATEGY="break" # 默认为 break

# 默认从第2行开始（跳过标题行）
START_LINE=2

# 默认模式为 generate
MODE="generate"

# 解析命令行选项
while getopts ":cs:m:" opt; do # 注意 's:' 和 'm:' 表示 s 和 m 选项需要参数
    case ${opt} in
        c )
            ON_ERROR_STRATEGY="continue"
            ;;
        s )
            # 验证 START_LINE 是否为正整数
            if ! [[ "$OPTARG" =~ ^[0-9]+$ ]] || [ "$OPTARG" -lt 1 ]; then
                echo "Invalid start line: '$OPTARG'. Must be a positive integer."
                exit 1
            fi
            START_LINE="$OPTARG"
            ;;
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

# 设置变量
BASE_PATH=$(readlink -f "$(dirname "${BASH_SOURCE[0]}")/../..")
CSV_FILE="$BASE_PATH/.claude/data/$1"
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

# 调用Claude生成测试脚本
claude_generate_test() {
    local claude_command="$1"
    local test_file="$2"
    local retry_count="${3:-0}"  # 添加重试计数参数，默认为0

    # 设置最大重试次数
    local max_retries=24

    # 检查是否超过最大重试次数
    if [ "$retry_count" -ge "$max_retries" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 达到最大重试次数($max_retries)，放弃处理第 $CURRENT_LINE 行" | tee -a "$LOG_FILE"
        return 1
    fi

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 执行Claude命令: $claude_command" | tee -a "$LOG_FILE"

    # 执行Claude命令并捕获输出
    local claude_output
    if claude_output=$($claude_command < /dev/null 2>&1); then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [SUCCESS] Claude命令执行成功" | tee -a "$LOG_FILE"

        # 记录测试文件已生成到日志
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件已生成: $test_file" | tee -a "$LOG_FILE"

        # 记录详细的Claude输出到日志
        echo "$(date '+%Y-%m-%d %H:%M:%S') [DEBUG] Claude输出内容:" | tee -a "$LOG_FILE"
        echo "--- 开始输出 ---" | tee -a "$LOG_FILE"
        echo "$claude_output" | tee -a "$LOG_FILE"
        echo "--- 结束输出 ---" | tee -a "$LOG_FILE"

        ((TOTAL_GENERATED++))
    else
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] Claude命令执行失败" | tee -a "$LOG_FILE"
        echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 错误信息: $claude_output" | tee -a "$LOG_FILE"
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 等待5分钟后重试..." | tee -a "$LOG_FILE"

        # 等待5分钟
        sleep 300

        # 递归重试
        claude_generate_test "$claude_command" "$test_file" "$((retry_count + 1))"

        # 返回递归调用的结果
        return $?
    fi

    return 0
}

# 处理单个方法的单元测试生成
process_method() {
    local module="$1"
    local class="$2"
    local method="$3"

    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 处理第 $CURRENT_LINE 行: 模块=$module, 类=$class, 方法=$method" | tee -a "$LOG_FILE"

    # 构建单元测试指导文件路径
    local guide_file="$BASE_PATH/.claude/rules/unit-test-$class-guide.md"

    # 构建测试文件路径（方法名转为小写）
    local method_lower=$(echo "$method" | tr '[:upper:]' '[:lower:]')
    local test_file="$BASE_PATH/module/$module/test/$class/$method_lower.php"

    # 检查测试文件是否存在
    if [ -f "$test_file" ]; then
        if [ "$MODE" == "generate" ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件已存在，跳过: $test_file" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
            return 0
        else
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件已存在，将在 $MODE 模式下处理: $test_file" | tee -a "$LOG_FILE"
        fi
    else
        if [ "$MODE" == "generate" ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 测试文件不存在，开始生成: $test_file" | tee -a "$LOG_FILE"
        else
            echo "$(date '+%Y-%m-%d %H:%M:%S') [WARN] 测试文件不存在，无法进行 $MODE 操作，跳过: $test_file" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
            return 0
        fi
    fi

    # 创建测试目录（如果不存在）
    local test_dir="$BASE_PATH/module/$module/test/$class"
    if [ ! -d "$test_dir" ]; then
        echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 创建测试目录: $test_dir" | tee -a "$LOG_FILE"
        mkdir -p "$test_dir"
    fi

    # 在 enhance 模式下检查测试步骤数量
    if [ "$MODE" == "enhance" ]; then
        # 使用 grep 和正则表达式查找符合 r(...) && p(...) && e(...); 模式的行
        local step_count=$(grep -E 'r\(.*\).*&&.*p\(.*\).*&&.*e\(.*\)' "$test_file" | wc -l)

        if [ "$step_count" -ge 5 ]; then
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 检测到 $step_count 个测试步骤，已满足要求，跳过增强处理: $test_file" | tee -a "$LOG_FILE"
            ((TOTAL_SKIPPED++))
            return 0
        else
            echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 检测到 $step_count 个测试步骤，未达到5个，将继续进行增强处理。" | tee -a "$LOG_FILE"
        fi
    fi

    # 根据模式构建不同的Claude提示词
    local claude_prompt=""
    case "$MODE" in
        "generate")
            claude_prompt="根据$guide_file文档的内容为$module模块的$class.php文件中的$method方法生成单元测试脚本。"
            ;;
        "enhance")
            claude_prompt="根据$guide_file文档的内容，对$module模块的$class.php文件中$method方法对应的现有单元测试脚本($test_file)进行改进和优化，提升测试覆盖率和代码质量并确保测试步骤不少于5个。"
            ;;
        "fix")
            claude_prompt="根据$guide_file文档的内容，检查并修复$module模块的$class.php文件中$method方法对应的现有单元测试脚本($test_file)中的错误和问题，确保测试能够稳定通过。"
            ;;
        *)
            # This should never happen due to validation in getopts
            echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 未知的运行模式: $MODE" | tee -a "$LOG_FILE"
            return 1
            ;;
    esac

    local claude_command="claude -p \"$claude_prompt\" --dangerously-skip-permissions < /dev/null 2>&1"

    # 调用Claude生成测试脚本
    claude_generate_test "$claude_command" "$test_file"

    # 返回Claude生成测试脚本的结果
    return $?
}

# 主处理函数
main() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] ========== 开始执行单元测试生成脚本 ==========" | tee -a "$LOG_FILE"
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] 运行模式: $MODE" | tee -a "$LOG_FILE"

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

        # method只能包含字母和数字且必须以小写字母开头
        if ! [[ "$method" =~ ^[a-z][a-zA-Z0-9_]*$ ]]; then
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
        else
            echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] 第 $CURRENT_LINE 行处理失败" | tee -a "$LOG_FILE"

            # 根据错误处理策略决定是否继续
            if [ "$ON_ERROR_STRATEGY" == "break" ]; then
                break
            else
                continue
            fi
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
    echo "$(date '+%Y-%m-%d %H:%M:%S') [STATS] 生成/更新数量: $TOTAL_GENERATED" | tee -a "$LOG_FILE"
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
