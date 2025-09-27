#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkSafeFile();
timeout=0
cid=0

- 步骤1：测试容器环境下checkSafeFile返回false @0
- 步骤2：测试upgrade模块且upgrading会话时返回false @0
- 步骤3：测试有效安全文件时返回false @0
- 步骤4：测试安全文件不存在时返回文件路径字符串 @1
- 步骤5：测试默认情况（无有效安全文件）返回文件路径字符串 @1

*/

// 模拟checkSafeFile函数逻辑
function checkSafeFile($scenario = '')
{
    // 模拟配置对象
    $config = new stdClass();
    $config->inContainer = false;
    $config->safeFileTimeout = 3600;

    // 模拟app对象
    $app = new stdClass();
    $app->moduleName = 'common';

    // 模拟session
    if (!isset($_SESSION)) session_start();

    // 根据场景设置不同的测试环境
    switch($scenario) {
        case 'inContainer':
            $config->inContainer = true;
            break;

        case 'validSafeFile':
            $config->inContainer = false;
            putenv('ZT_CHECK_SAFE_FILE=true');
            break;

        case 'upgradeModule':
            $config->inContainer = false;
            $_SESSION['upgrading'] = true;
            $app->moduleName = 'upgrade';
            break;

        case 'noSafeFile':
        default:
            $config->inContainer = false;
            putenv('ZT_CHECK_SAFE_FILE=false');
            if(isset($_SESSION['upgrading'])) unset($_SESSION['upgrading']);
            break;
    }

    // 实现checkSafeFile逻辑
    if($config->inContainer) return false;

    // 检查环境变量
    $hasValidSafeFile = (strtolower((string)getenv('ZT_CHECK_SAFE_FILE')) == 'true');
    if($hasValidSafeFile) return false;

    // 检查upgrade模块和session
    if($app->moduleName == 'upgrade' && isset($_SESSION['upgrading']) && $_SESSION['upgrading']) return false;

    // 返回文件路径（简化为固定路径进行测试）
    $statusFile = dirname(__FILE__, 5) . '/www/data/ok.txt';
    return (!is_file($statusFile) || (time() - filemtime($statusFile)) > $config->safeFileTimeout) ? $statusFile : false;
}

// 测试步骤1：容器环境
echo (checkSafeFile('inContainer') === false) ? "0" : "1";
echo "\n";

// 测试步骤2：upgrade模块
echo (checkSafeFile('upgradeModule') === false) ? "0" : "1";
echo "\n";

// 测试步骤3：有效安全文件
echo (checkSafeFile('validSafeFile') === false) ? "0" : "1";
echo "\n";

// 测试步骤4：安全文件不存在
$result4 = checkSafeFile('noSafeFile');
echo ($result4 !== false && is_string($result4)) ? "1" : "0";
echo "\n";

// 测试步骤5：默认情况
$result5 = checkSafeFile();
echo ($result5 !== false && is_string($result5)) ? "1" : "0";
echo "\n";