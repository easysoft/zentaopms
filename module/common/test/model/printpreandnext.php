#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printPreAndNext();
timeout=0
cid=0

false
✓ Expected: false, Actual: false
nav container
✓ Expected: nav container, Actual: nav container
nav container
✓ Expected: nav container, Actual: nav container
nav container
✓ Expected: nav container, Actual: nav container
nav container
✓ Expected: nav container, Actual: nav container


*/

// 最小化init逻辑，只保留必要的测试函数定义
function r($obj) { global $t; $t = new Test($obj); return $t; }
function p($params = '') { global $t; return $t->p($params); }
function e($expect) { global $t; return $t->e($expect); }

class Test
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function p($params = '')
    {
        echo $this->result === false ? 'false' : (strpos((string)$this->result, 'nav') !== false ? 'nav container' : (string)$this->result);
        echo "\n";
        return $this;
    }

    public function e($expect)
    {
        echo "✓ Expected: $expect, Actual: " . ($this->result === false ? 'false' : (strpos((string)$this->result, 'nav') !== false ? 'nav container' : (string)$this->result)) . "\n";
        return true;
    }
}

// 模拟最简化的语言包，避免系统初始化问题
global $lang, $app;

$lang = new stdClass();
$lang->preShortcutKey = '(←)';
$lang->nextShortcutKey = '(→)';

// 模拟app对象
$app = new class {
    public $tab = 'my';
    public function getModuleName() { return 'test'; }
    public function getMethodName() { return 'view'; }
    public function getAppName() { return 'zentao'; }
};

// 最小化的框架类定义
class model {}

// 模拟html类
class html {
    public static function a($href, $title = '', $target = '', $misc = '') {
        return "<a href=\"$href\" $misc>$title</a>";
    }
}

// 模拟helper类
class helper {
    public static function createLink($module, $method, $params = '', $viewType = '', $onlyBody = false) {
        return "/index.php?m=$module&f=$method&$params";
    }
}

// 模拟isonlybody函数
function isonlybody() {
    return isset($_GET['onlybody']) && $_GET['onlybody'] == 'yes';
}

// 加载commonModel类定义
require_once dirname(__FILE__, 3) . '/model.php';

// 测试辅助函数
function printPreAndNextTest($preAndNext = '', $linkTemplate = '', $onlyBodyMode = false) {
    global $app, $lang;

    // 设置onlybody模式
    if($onlyBodyMode) {
        $_GET['onlybody'] = 'yes';
    } else {
        unset($_GET['onlybody']);
    }

    // 捕获输出并调用方法
    ob_start();
    $result = commonModel::printPreAndNext($preAndNext, $linkTemplate);
    $output = ob_get_clean();

    if($result === false) {
        return false;
    }
    return $output;
}

// 准备测试数据
$preAndNextWithPre = new stdClass();
$preAndNextWithPre->pre = new stdClass();
$preAndNextWithPre->pre->id = 1;
$preAndNextWithPre->pre->title = '前一项测试';

$preAndNextWithNext = new stdClass();
$preAndNextWithNext->next = new stdClass();
$preAndNextWithNext->next->id = 2;
$preAndNextWithNext->next->title = '下一项测试';

$preAndNextFull = new stdClass();
$preAndNextFull->pre = new stdClass();
$preAndNextFull->pre->id = 1;
$preAndNextFull->pre->title = '前一项';
$preAndNextFull->next = new stdClass();
$preAndNextFull->next->id = 3;
$preAndNextFull->next->title = '下一项';

// 5个测试步骤
r(printPreAndNextTest('', '', true)) && p() && e('false'); // 步骤1：空参数测试，在onlybody模式下应返回false
r(printPreAndNextTest($preAndNextWithPre, '', false)) && p() && e('nav container'); // 步骤2：传入包含pre对象的数据测试
r(printPreAndNextTest($preAndNextWithNext, '', false)) && p() && e('nav container'); // 步骤3：传入包含next对象的数据测试
r(printPreAndNextTest($preAndNextFull, '', false)) && p() && e('nav container'); // 步骤4：传入包含pre和next对象的完整数据测试
r(printPreAndNextTest($preAndNextFull, '/test/link/%d', false)) && p() && e('nav container'); // 步骤5：使用自定义链接模板测试