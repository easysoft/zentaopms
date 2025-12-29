<?php
/**
 * PHP 隐式可空类型修复脚本
 * 用于修复 RFC: Deprecate implicitly nullable types 问题
 * https://wiki.php.net/rfc/deprecate-implicitly-nullable-types
 *
 * 功能特点：
 * - 支持所有PHP类型（string, int, array等内置类型和自定义类）
 * - 支持命名空间类型（\Namespace\ClassName）
 * - 支持跨行函数参数
 * - 自动跳过已有?的参数
 * - 自动跳过 mixed 类型的参数
 * - 自动跳过联合类型中已包含null的参数
 * - 完整日志记录
 */

class NullableTypesFixer
{
    private $logFile;
    private $processedFiles = 0;
    private $modifiedFiles = 0;
    private $totalChanges = 0;
    private $dryRun = false;

    public function __construct($logFile = null, $dryRun = false)
    {
        $this->logFile = $logFile ?? __DIR__ . '/nullable_types_fix_' . date('Y-m-d_H-i-s') . '.log';
        $this->dryRun = $dryRun;
        $this->log("========================================");
        $this->log("隐式可空类型修复脚本启动 - 最终版本");
        $this->log("模式: " . ($dryRun ? "预览模式（不会修改文件）" : "修复模式"));
        $this->log("时间: " . date('Y-m-d H:i:s'));
        $this->log("========================================\n");
    }

    /**
     * 记录日志
     */
    private function log($message, $level = 'INFO')
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}\n";

        // 输出到控制台
        echo $logMessage;

        // 写入日志文件
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    /**
     * 扫描目录中的所有PHP文件
     */
    public function scanDirectory($directory)
    {
        if(!is_dir($directory))
        {
            $this->log("错误: 目录不存在: {$directory}", 'ERROR');
            return;
        }

        $this->log("开始扫描目录: {$directory}");

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach($iterator as $file)
        {
            if($file->isFile() && $file->getExtension() === 'php')
            {
                $this->processFile($file->getPathname());
            }
        }

        $this->printSummary();
    }

    /**
     * 处理单个PHP文件
     */
    private function processFile($filePath)
    {
        $this->processedFiles++;
        $this->log("\n处理文件 [{$this->processedFiles}]: {$filePath}");

        $content = file_get_contents($filePath);
        if($content === false)
        {
            $this->log("错误: 无法读取文件: {$filePath}", 'ERROR');
            return;
        }

        $lines = explode("\n", $content);
        $modified = false;
        $fileChanges = 0;

        foreach($lines as $lineNum => &$line)
        {
            $lineNumber = $lineNum + 1;

            // 匹配参数声明：类型 $变量 = null
            // 支持：(Type $var, Type $var, 行首的Type $var
            // 类型可以是：string, int, array 等小写内置类型，或 ClassName, \Namespace\ClassName
            $pattern = '/(^\s*|[\(,]\s*)(\\\\?[A-Za-z][\w\\\\]*)\s+(\$\w+)\s*=\s*(null|NULL)\b/';

            if(preg_match_all($pattern, $line, $matches, PREG_OFFSET_CAPTURE))
            {
                // 从后往前替换，避免位置偏移问题
                for($i = count($matches[0]) - 1; $i >= 0; $i--)
                {
                    $fullMatch = $matches[0][$i];
                    $matchPos = $fullMatch[1];
                    $prefix = $matches[1][$i][0];
                    $type = $matches[2][$i][0];
                    $variable = $matches[3][$i][0];
                    $nullValue = $matches[4][$i][0];

                    // 检查类型前面是否已经有 ?
                    $beforeType = substr($line, 0, $matchPos + strlen($prefix));
                    if(preg_match('/\?\s*$/', $beforeType))
                    {
                        continue; // 已经有 ? 了
                    }

                    // 检查是否是 mixed 类型
                    if($type == 'mixed')
                    {
                        continue;
                    }

                    // 检查是否是联合类型且已包含 null
                    if(stripos($type, '|null') !== false || stripos($type, 'null|') !== false)
                    {
                        continue;
                    }

                    // 检查是否在函数参数中
                    if(!$this->isInFunctionParams($line, $matchPos))
                    {
                        continue;
                    }

                    $fileChanges++;
                    $modified = true;

                    $this->log("  发现问题 #{$fileChanges}:");
                    $this->log("    位置: 第 {$lineNumber} 行");
                    $this->log("    原代码: {$type} {$variable} = {$nullValue}");
                    $this->log("    修复为: ?{$type} {$variable} = {$nullValue}");

                    // 在类型前面添加 ?
                    $replacement = $prefix . '?' . $type . ' ' . $variable . ' = ' . $nullValue;
                    $line = substr_replace($line, $replacement, $matchPos, strlen($fullMatch[0]));
                }
            }
        }
        unset($line); // 解除引用

        if($modified)
        {
            $this->modifiedFiles++;
            $this->totalChanges += $fileChanges;

            if(!$this->dryRun)
            {
                // 写入修复后的内容
                $newContent = implode("\n", $lines);
                if(file_put_contents($filePath, $newContent) === false)
                {
                    $this->log("  错误: 无法写入文件: {$filePath}", 'ERROR');
                }
                else
                {
                    $this->log("  成功修复 {$fileChanges} 个问题", 'SUCCESS');
                }
            }
            else
            {
                $this->log("  [预览模式] 发现 {$fileChanges} 个需要修复的问题", 'INFO');
            }
        }
        else
        {
            $this->log("  未发现问题");
        }
    }

    /**
     * 检查匹配位置是否在函数参数中
     */
    private function isInFunctionParams($line, $matchPos)
    {
        // 如果这一行包含 function 关键字，肯定是函数参数
        if(stripos($line, 'function') !== false)
        {
            return true;
        }

        // 检查是否在括号中（可能是跨行的函数参数）
        $beforeMatch = substr($line, 0, $matchPos);
        $openParens = substr_count($beforeMatch, '(');
        $closeParens = substr_count($beforeMatch, ')');

        // 如果左括号多于右括号，说明在参数列表中
        if($openParens > $closeParens)
        {
            return true;
        }

        // 对于跨行参数（行首有空格+类型声明的情况）
        // 这种模式几乎只出现在函数参数中
        $stripped = trim($line);
        if(!empty($stripped))
        {
            // 检查是否以内置类型或大写字母开头（类名）
            $builtinTypes = ['string', 'int', 'bool', 'float', 'array', 'object',
                           'callable', 'iterable', 'mixed'];
            foreach($builtinTypes as $type)
            {
                if(strpos($stripped, $type . ' ') === 0)
                {
                    return true;
                }
            }
            // 检查是否以反斜杠或大写字母开头，并包含 $
            if((substr($stripped, 0, 1) === '\\' || ctype_upper(substr($stripped, 0, 1)))
                && strpos($stripped, ' $') !== false)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * 打印汇总信息
     */
    private function printSummary()
    {
        $this->log("\n========================================");
        $this->log("处理完成汇总");
        $this->log("========================================");
        $this->log("扫描文件总数: {$this->processedFiles}");
        $this->log("修改文件数量: {$this->modifiedFiles}");
        $this->log("修复问题总数: {$this->totalChanges}");
        $this->log("日志文件位置: {$this->logFile}");
        $this->log("========================================\n");
    }
}

// 使用示例
if($argc < 2)
{
    echo "用法: php fix_nullable_types.php <目录路径> [--dry-run]\n";
    echo "参数说明:\n";
    echo "  <目录路径>  要扫描的PHP文件目录\n";
    echo "  --dry-run   预览模式，不实际修改文件\n";
    echo "\n示例:\n";
    echo "  php fix_nullable_types.php /path/to/project\n";
    echo "  php fix_nullable_types.php /path/to/project --dry-run\n";
    exit(1);
}

$directory = $argv[1];
$dryRun = isset($argv[2]) && $argv[2] === '--dry-run';

$fixer = new NullableTypesFixer(null, $dryRun);
$fixer->scanDirectory($directory);
