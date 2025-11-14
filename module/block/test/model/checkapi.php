#!/usr/bin/env php
<?php

/**

title=测试 blockModel::checkAPI();
timeout=0
cid=15224

- 执行blockTest模块的checkAPITest方法，参数是''  @0
- 执行blockTest模块的checkAPITest方法，参数是'858640a724c2c981983935eb2bbc4ad8'  @1
- 执行blockTest模块的checkAPITest方法，参数是'858640a724c2c98198wrong'  @0
- 执行blockTest模块的checkAPITest方法，参数是'a'  @0
- 执行blockTest模块的checkAPITest方法，参数是'123456789'  @0
- 执行blockTest模块的checkAPITest方法，参数是'858640a724c2c981983935eb2bbc4ad8'  @0
- 执行blockTest模块的checkAPITest方法，参数是'858640a724c2c981983935eb2bbc4ad8858640a724c2c981983935eb2bbc4ad8858640a724c2c981983935eb2bbc4ad8'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

function initValidConfig()
{
    $config = zenData('config');
    $config->id->range('1');
    $config->owner->range('system');
    $config->module->range('sso');
    $config->key->range('key');
    $config->value->range('858640a724c2c981983935eb2bbc4ad8');
    $config->gen(1);
}

function initEmptyConfig()
{
    $config = zenData('config');
    $config->gen(0);
}

su('admin');

$blockTest = new blockTest();

// 步骤1：测试空字符串哈希值情况
initValidConfig();
r($blockTest->checkAPITest('')) && p('') && e('0');

// 步骤2：测试正确哈希值匹配情况
initValidConfig();
r($blockTest->checkAPITest('858640a724c2c981983935eb2bbc4ad8')) && p('') && e('1');

// 步骤3：测试错误哈希值不匹配情况
initValidConfig();
r($blockTest->checkAPITest('858640a724c2c98198wrong')) && p('') && e('0');

// 步骤4：测试单个字符哈希值情况
initValidConfig();
r($blockTest->checkAPITest('a')) && p('') && e('0');

// 步骤5：测试数字字符哈希值情况
initValidConfig();
r($blockTest->checkAPITest('123456789')) && p('') && e('0');

// 步骤6：测试数据库配置不存在情况
initEmptyConfig();
r($blockTest->checkAPITest('858640a724c2c981983935eb2bbc4ad8')) && p('') && e('0');

// 步骤7：测试超长哈希值输入情况
initValidConfig();
r($blockTest->checkAPITest('858640a724c2c981983935eb2bbc4ad8858640a724c2c981983935eb2bbc4ad8858640a724c2c981983935eb2bbc4ad8')) && p('') && e('0');