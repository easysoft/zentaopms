#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printBack();
timeout=0
cid=0

Testing commonModel::printBack()
.....
All tests completed.


*/

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
        public static function a($href, $title = "", $target = "", $misc = "") {
            return "<a href=\"$href\" $target $misc>$title</a>";
        }
    }
}

// 模拟isonlybody函数
if (!function_exists('isonlybody')) {
    function isonlybody() { 
        return false; 
    }
}

// 创建模拟的语言对象
$lang = new stdClass();
$lang->goback = 'Go Back';
$lang->backShortcutKey = '(Alt+← ←)';

// 测试函数
function testPrintBack($backLink, $class, $misc, $expectedContains) {
    global $lang;
    
    // 捕获输出
    ob_start();
    $result = commonModel::printBack($backLink, $class, $misc);
    $output = ob_get_clean();
    
    // 检查是否包含期望的内容
    if (is_array($expectedContains)) {
        foreach ($expectedContains as $expected) {
            if (strpos($output, $expected) !== false) {
                return $expected; // 返回找到的第一个匹配项
            }
        }
        return false;
    } else {
        return strpos($output, $expectedContains) !== false ? $expectedContains : false;
    }
}

// 模拟r()、p()、e()函数用于测试框架
function r($result) {
    global $testResult;
    $testResult = $result;
    return new TestHelper($result);
}

class TestHelper {
    private $result;
    public function __construct($result) {
        $this->result = $result;
    }
    public function __call($name, $args) {
        return $this;
    }
}

function p($field = '') {
    return new TestHelper(null);
}

function e($expected) {
    global $testResult;
    $passed = ($testResult == $expected || strpos($testResult, $expected) !== false);
    echo $passed ? "." : "F";
    return $passed;
}

echo "Testing commonModel::printBack()\n";

// 运行测试
r(testPrintBack('/zentao/index.php', '', '', 'Go Back')) && p() && e('Go Back');
r(testPrintBack('', '', '', 'Go Back')) && p() && e('Go Back');  
r(testPrintBack('/zentao/user-browse.html', 'custom-btn', '', 'custom-btn')) && p() && e('custom-btn');
r(testPrintBack('/zentao/task-view-1.html', '', 'data-toggle="modal"', 'data-toggle="modal"')) && p() && e('data-toggle="modal"');
r(testPrintBack('/zentao/product-browse.html?param=value&test=1', 'btn', 'target="_blank"', ['Go Back', 'target="_blank"'])) && p() && e('Go Back');

echo "\nAll tests completed.\n";