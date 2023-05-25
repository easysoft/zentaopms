#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

function initData()
{
    $block = zdTable('config');
    $block->owner->range('admin');
    $block->vision->range('rnd');
    $block->module->range('my');
    $block->section->range('common');
    $block->key->range('blockInited');

    $block->gen(1);
}

/**

title=测试 blockModel->getBlockInitStatus();
timeout=0
cid=1

- 测试 my 模块下返回为 1 @1

- 测试 product 模块下返回为 0 @0

- 测试不存在模块返回为 0 @0

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getBlockInitStatus('my'))      && p('') && e('1');   //  测试 my 模块下返回为 1
r($tester->block->getBlockInitStatus('product')) && p('') && e('0');   //  测试 product 模块下返回为 0
r($tester->block->getBlockInitStatus('asda??'))  && p('') && e('0');   //  测试不存在模块返回为 0