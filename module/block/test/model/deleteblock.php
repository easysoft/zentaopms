#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

function initData()
{
    $block = zenData('block');
    $block->id->range('2-5');
    $block->account->range('admin');
    $block->vision->range('rnd,lite');
    $block->dashboard->range('my');
    $block->module->range('project');
    $block->code->range('list,statistic');
    $block->title->prefix('区块名称')->range('2-5');

    $block->gen(4);
}

/**

title=测试 block 模块的update 方法
timeout=0
cid=15227

- 测试ID为2的区块是否存在属性id @2

- 测试ID为2的区块删除后的返回结果 @1

- 测试ID为2的区块的是否存在属性id @0

- 测试ID为3的区块的是否存在属性id @3

- 测试根据代号删除区块后的返回结果 @1

- 测试ID为3的区块的是否存在属性id @0

- 测试ID为22的区块删除后的返回结果 @1

*/

global $tester;
$tester->loadModel('block');

initData();

$blockTest = new blockModelTest();
r($tester->block->getByID(2)) && p('id') && e('2');            // 测试ID为2的区块是否存在
r($tester->block->deleteBlock(2, '', '')) && p('') && e('1');  // 测试ID为2的区块删除后的返回结果
r($tester->block->getByID(2)) && p('id') && e('0');            // 测试ID为2的区块的是否存在
r($tester->block->getByID(3)) && p('id') && e('3');            // 测试ID为3的区块的是否存在
r($tester->block->deleteBlock(0, 'project', 'statistic')) && p('') && e('1');  // 测试根据代号删除区块后的返回结果
r($tester->block->getByID(3)) && p('id') && e('0');    // 测试ID为3的区块的是否存在
r($tester->block->deleteBlock(22)) && p('') && e('1'); // 测试ID为22的区块删除后的返回结果
