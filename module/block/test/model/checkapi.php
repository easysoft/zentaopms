#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

function initData()
{
    $config = zdTable('config');
    $config->id->range('1');
    $config->owner->range('system');
    $config->module->range('sso');
    $config->key->range('key');
    $config->value->range('858640a724c2c981983935eb2bbc4ad8');

    $config->gen(1);
}

/**

title=测试 blockModel->checkAPI();
timeout=0
cid=1

- 测试空哈希值 @0

- 测试正确的哈希值 @1

- 测试错误的哈希值 @0

*/

initData();

$block = new blockTest();

r($block->checkAPITest('')) && p('') && e('0');                                 // 测试空哈希值
r($block->checkAPITest('858640a724c2c981983935eb2bbc4ad8')) && p('') && e('1'); // 测试正确的哈希值
r($block->checkAPITest('858640a724c2c98198')) && p('') && e('0');               // 测试错误的哈希值
