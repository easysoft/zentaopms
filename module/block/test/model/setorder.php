#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
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
    $block->order->range('1-4');

    $block->gen(4);
}

/**

title=测试 block 模块的update 方法
timeout=0
cid=39

- 测试ID为2的区块变更排序后的返回结果 @1

- 测试ID为2的区块的序号
 - 属性id @2
 - 属性order @3

- 测试ID为2的区块变更排序后的返回结果 @1

- 测试ID为3的区块的序号
 - 属性id @3
 - 属性order @1

*/

global $tester;
$tester->loadModel('block');

initData();

$block1 = new stdclass();
$block1->id    = '2';
$block1->order = '3';

$block2 = new stdclass();
$block2->id    = '3';
$block2->order = '1';

$blockTest = new blockTest();
r($tester->block->setOrder($block1->id, $block1->order)) && p('') && e('1'); // 测试ID为2的区块变更排序后的返回结果
r($tester->block->getByID($block1->id)) && p('id,order') && e("2,3"); // 测试ID为2的区块的序号

r($tester->block->setOrder($block2->id, $block2->order)) && p('') && e('1'); // 测试ID为2的区块变更排序后的返回结果
r($tester->block->getByID($block2->id)) && p('id,order') && e("3,1"); // 测试ID为3的区块的序号