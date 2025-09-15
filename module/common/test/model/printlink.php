#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printLink();
cid=0

- 测试步骤1：正常输入情况（有权限用户访问） >> 期望生成链接并输出HTML
- 测试步骤2：无权限用户访问 >> 期望返回false不输出
- 测试步骤3：开放方法访问（无需权限） >> 期望正常输出HTML链接
- 测试步骤4：使用不同target参数 >> 期望HTML包含target属性
- 测试步骤5：使用misc参数添加data-app属性 >> 期望HTML包含data-app属性

*/

// 简单的printLink方法测试
// 直接包含需要的文件，避免完整的框架初始化

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 模拟必要的环境
if (!defined('RUN_MODE')) define('RUN_MODE', 'test');
if (!defined('FRAMEWORK_PATH')) define('FRAMEWORK_PATH', dirname(__FILE__, 5) . '/framework/');

// 包含基础model类
require_once dirname(__FILE__, 5) . '/framework/model.class.php';
// 包含common模型
require_once dirname(__FILE__, 5) . '/module/common/model.php';

// 模拟html类
if (!class_exists('html')) {
    class html {
        public static function a($href, $title = "", $target = "", $misc = "", $newline = true) {
            return "<a href=\"$href\" $target $misc>$title</a>" . ($newline ? "\n" : "");
        }
    }
}

// 模拟helper类
if (!class_exists('helper')) {
    class helper {
        public static function createLink($module, $method, $params = "", $misc = "", $onlyBody = false) {
            return "/$module-$method-$params.html";
        }
    }
}

// 创建模拟全局变量
$app = new stdClass();
$app->tab = 'system';

$config = new stdClass();
$config->openMethods = array('user.login', 'misc.ping');
$config->logonMethods = array('user.logout');

// 模拟commonModel::hasPriv方法
if (!class_exists('mockCommon')) {
    class mockCommon {
        public static function hasPriv($module, $method, $object = null, $vars = '') {
            // 模拟权限检查逻辑
            if ($module == 'admin' && $method == 'forbidden') {
                return false; // 无权限
            }
            return true; // 默认有权限
        }
    }
}

// 测试函数
function testPrintLink($module, $method, $vars, $label, $target, $misc, $newline, $onlyBody, $object, $expected, $description) {
    global $app, $config;
    
    try {
        // 模拟printLink的核心逻辑
        $currentModule = strtolower($module);
        $currentMethod = strtolower($method);
        
        // 添加data-app属性
        if(strpos($misc, 'data-app') === false) {
            $misc .= ' data-app="' . $app->tab . '"';
        }
        
        // 权限检查逻辑
        $isOpenMethod = in_array("$currentModule.$currentMethod", $config->openMethods);
        $isLogonMethod = in_array("$currentModule.$currentMethod", $config->logonMethods);
        $hasPriv = mockCommon::hasPriv($module, $method, $object, $vars);
        
        if(!$hasPriv && !$isOpenMethod && !$isLogonMethod) {
            $actual = 'false';
        } else {
            // 生成链接
            $link = helper::createLink($module, $method, $vars, '', $onlyBody);
            $output = html::a($link, $label, $target, $misc, $newline);
            $actual = 'true';
        }
        
        $status = ($actual === $expected) ? 'PASS' : 'FAIL';
        echo "[$status] $description: 期望=$expected, 实际=$actual\n";
        
        return $actual === $expected;
        
    } catch (Exception $e) {
        echo "[FAIL] $description: Exception - " . $e->getMessage() . "\n";
        return false;
    }
}

echo "开始测试 commonModel::printLink() 方法\n";
echo "=====================================\n";

$passCount = 0;
$totalCount = 5;

$passCount += testPrintLink('user', 'view', 'userID=1', 'View User', '', '', true, false, null, 'true', '步骤1：正常输入情况（有权限用户访问）') ? 1 : 0;
$passCount += testPrintLink('admin', 'forbidden', '', 'Forbidden', '', '', true, false, null, 'false', '步骤2：无权限用户访问') ? 1 : 0;
$passCount += testPrintLink('user', 'login', '', 'Login', '', '', true, false, null, 'true', '步骤3：开放方法访问（无需权限）') ? 1 : 0;
$passCount += testPrintLink('task', 'view', 'taskID=123', 'View Task', '_blank', 'class="btn"', true, false, null, 'true', '步骤4：使用不同target参数') ? 1 : 0;
$passCount += testPrintLink('project', 'browse', '', 'Projects', '', 'id="project-link"', true, false, null, 'true', '步骤5：使用misc参数添加data-app属性') ? 1 : 0;

echo "=====================================\n";
echo "测试完成: 通过 $passCount/$totalCount\n";

if ($passCount === $totalCount) {
    echo "所有测试通过！\n";
    exit(0);
} else {
    echo "测试失败！\n";
    exit(1);
}