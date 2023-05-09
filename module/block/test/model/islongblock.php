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
    $block->dashboard->range('my');
    $block->module->range('project');
    $block->code->range('list,statistic');
    $block->title->prefix('区块名称')->range('2-5');
    $block->grid->range('4,8');
    $block->order->range('1-4');

    $block->gen(4);
}

/**

title=测试 blockModel->isLongBlock();
timeout=0
cid=1

- 测试空对象是否为长区块 @0

- 测试ID为2的对象是否为长区块 @0

- 测试ID为3的对象是否为长区块 @1

*/

global $tester;
$tester->loadModel('block');

initData();

$emptyObject = new stdclass();

r($tester->block->isLongBlock($emptyObject)) && p() && e('0');               // 测试空对象是否为长区块
r($tester->block->isLongBlock($tester->block->getByID(2))) && p() && e('0'); // 测试ID为2的对象是否为长区块
r($tester->block->isLongBlock($tester->block->getByID(3))) && p() && e('1'); // 测试ID为3的对象是否为长区块