#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-5');
    $block->account->range('system');
    $block->vision->range('rnd');
    $block->dashboard->range('zentao');
    $block->module->range('zentao');
    $block->code->range('plugin,patch,publicclass,news');
    $block->createdDate->range('`2020-01-01`,`2021-01-01`');
    $block->title->prefix('区块')->range('2-5');
    $block->order->range('2-5');

    $block->gen(4);
}

/**

title=测试 block 模块 model下的 getMyDashboard 方法
timeout=0
cid=39

- 查询admin用户在我的地盘仪表盘的区块列表数量 @4

- 查询admin用户在我的地盘仪表盘的区块列表数量 @2

*/

global $tester;
$tester->loadModel('block');

initData();

r(count($tester->block->getAdminBlockList())) && p('') && e('4');             // 查询admin用户在我的地盘仪表盘的区块列表数量
r(count($tester->block->getAdminBlockList('2021-01-01'))) && p('') && e('2'); // 查询admin用户在我的地盘仪表盘的区块列表数量