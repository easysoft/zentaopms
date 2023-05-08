#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

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

title=测试 blockModel->getMaxOrderByDashboard();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getMaxOrderByDashboard('my')) && p('') && e('4'); //  测试 my 模块下最大order 为 4
r($tester->block->getMaxOrderByDashboard('my')) && p('') && e('4'); //  测试 my 模块下最大order 为 4
r($tester->block->getMaxOrderByDashboard('bug')) && p('') && e('0'); // 测试数据库中不存在 模块的最大排序  为 0
