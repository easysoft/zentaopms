#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printCommentIcon();
timeout=0
cid=15691

- 测试步骤1：验证方法存在性 @1
- 测试步骤2：验证方法是静态方法 @1
- 测试步骤3：验证参数数量正确 @2
- 测试步骤4：验证第一个参数类型为string @string
- 测试步骤5：验证第二个参数可为null @1

*/

// 检查printCommentIcon方法的各项属性
function checkPrintCommentIcon()
{
    $modelFile = dirname(__FILE__, 3) . '/model.php';
    if (!file_exists($modelFile)) {
        echo "0\n0\n0\n0\n0\n";
        return;
    }

    // 读取文件内容而不执行
    $content = file_get_contents($modelFile);

    // 检查步骤1：方法是否存在
    $methodExists = strpos($content, 'function printCommentIcon') !== false ||
                   strpos($content, 'static function printCommentIcon') !== false ||
                   strpos($content, 'public static function printCommentIcon') !== false;
    echo $methodExists ? '1' : '0';
    echo "\n";

    // 检查步骤2：是否为静态方法
    $isStatic = strpos($content, 'static function printCommentIcon') !== false ||
               strpos($content, 'public static function printCommentIcon') !== false;
    echo $isStatic ? '1' : '0';
    echo "\n";

    // 检查步骤3：参数数量（通过正则匹配）
    if (preg_match('/function printCommentIcon\s*\([^)]*\)/', $content, $matches)) {
        $paramString = $matches[0];
        // 计算参数数量（简单方式：统计逗号数量+1，如果有参数的话）
        $paramString = substr($paramString, strpos($paramString, '(') + 1, -1);
        $paramString = trim($paramString);
        if (empty($paramString)) {
            echo "0";
        } else {
            $paramCount = substr_count($paramString, ',') + 1;
            echo $paramCount;
        }
    } else {
        echo "0";
    }
    echo "\n";

    // 检查步骤4：第一个参数类型为string
    $hasStringParam = strpos($content, 'string $commentFormLink') !== false;
    echo $hasStringParam ? 'string' : 'unknown';
    echo "\n";

    // 检查步骤5：第二个参数可为null
    $hasNullableParam = strpos($content, '?object $object') !== false;
    echo $hasNullableParam ? '1' : '0';
    echo "\n";
}

checkPrintCommentIcon();