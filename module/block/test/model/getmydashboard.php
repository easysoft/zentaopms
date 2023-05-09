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
    $block->dashboard->range('my,qa,project');
    $block->title->prefix('区块')->range('2-5');
    $block->order->range('5-2');

    $block->gen(4);
}

/**

title=测试 block 模块 model下的 getMyDashboard 方法
timeout=0
cid=39

- 查询admin用户在我的地盘仪表盘的区块列表数量 @1

- 查询admin用户在我的地盘仪表盘的区块标题第2条的title属性第2条的title属性 @区块2

- 查询admin用户在我的地盘仪表盘的隐藏区块列表数量 @0

- 查询admin用户在不存在仪表盘的区块列表数量 @0

- 查询admin用户在我的地盘仪表盘区块的vision第2条的vision属性第2条的vision属性 @rnd

*/

global $tester;
$tester->loadModel('block');

initData();

r(count($tester->block->getMyDashboard('my')))     && p('')         && e('1');       // 查询admin用户在我的地盘仪表盘的区块列表数量
r($tester->block->getMyDashboard('my'))            && p('2:title')  && e('区块2');   // 查询admin用户在我的地盘仪表盘的区块标题第2条的title属性
r(count($tester->block->getMyDashboard('my', 1)))  && p('')         && e('0');       // 查询admin用户在我的地盘仪表盘的隐藏区块列表数量
r(count($tester->block->getMyDashboard('asdas?'))) && p('')         && e('0');       // 查询admin用户在不存在仪表盘的区块列表数量
r($tester->block->getMyDashboard('my'))            && p('2:vision') && e('rnd');     // 查询admin用户在我的地盘仪表盘区块的vision第2条的vision属性