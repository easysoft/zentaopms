#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-5');
    $block->account->range('admin');
    $block->vision->range('rnd,lite');
    $block->dashboard->range('my,qa,project');
    $block->module->range('assingtome,bug,project');
    $block->code->range('assingtome,list,list');
    $block->title->prefix('区块')->range('2-5');
    $block->order->range('5-2');

    $block->gen(4);
}

/**

title=测试 block 模块 model下的 getMyDashboard 方法
timeout=0
cid=39

- 测试 assingtome 区块的 区块ID是否为2 @2

- 测试 buglist 区块的 区块ID是否为2 @3

- 测试 找不到的区块的 区块ID 是否为 false @0

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getSpecifiedBlockID('my', 'assingtome', 'assingtome'))  && p('') && e('2'); // 测试 assingtome 区块的 区块ID是否为2
r($tester->block->getSpecifiedBlockID('qa', 'bug', 'list'))  && p('') && e('3');              // 测试 buglist 区块的 区块ID是否为2
r($tester->block->getSpecifiedBlockID('my', 'welcome', 'welcome'))  && p('') && e('0');       // 测试 找不到的区块的 区块ID 是否为 false