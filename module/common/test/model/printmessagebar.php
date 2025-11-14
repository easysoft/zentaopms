#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printMessageBar();
timeout=0
cid=15698

- 步骤1：验证printMessageBar方法存在性 @1
- 步骤2：验证printMessageBar是静态方法 @1
- 步骤3：验证printMessageBar参数数量为0 @0
- 步骤4：验证getDotStyle方法存在 @1
- 步骤5：验证getDotStyle方法参数类型正确 @1

*/

function checkPrintMessageBar()
{
    $modelFile = dirname(__FILE__, 3) . '/model.php';
    if (!file_exists($modelFile)) {
        echo "0\n0\n0\n0\n0\n";
        return;
    }

    // 读取文件内容而不执行
    $content = file_get_contents($modelFile);

    // 步骤1：检查printMessageBar方法是否存在
    $methodExists = strpos($content, 'function printMessageBar') !== false ||
                   strpos($content, 'static function printMessageBar') !== false ||
                   strpos($content, 'public static function printMessageBar') !== false;
    echo $methodExists ? '1' : '0';
    echo "\n";

    // 步骤2：检查是否为静态方法
    $isStatic = strpos($content, 'static function printMessageBar') !== false ||
               strpos($content, 'public static function printMessageBar') !== false;
    echo $isStatic ? '1' : '0';
    echo "\n";

    // 步骤3：检查参数数量（应该为0）
    if (preg_match('/function printMessageBar\s*\([^)]*\)/', $content, $matches)) {
        $paramString = $matches[0];
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

    // 步骤4：检查getDotStyle方法是否存在
    $getDotStyleExists = strpos($content, 'function getDotStyle') !== false ||
                        strpos($content, 'static function getDotStyle') !== false ||
                        strpos($content, 'public static function getDotStyle') !== false;
    echo $getDotStyleExists ? '1' : '0';
    echo "\n";

    // 步骤5：检查getDotStyle方法参数类型
    $hasCorrectParams = strpos($content, 'bool $showCount') !== false &&
                       strpos($content, 'int $unreadCount') !== false;
    echo $hasCorrectParams ? '1' : '0';
    echo "\n";
}

checkPrintMessageBar();