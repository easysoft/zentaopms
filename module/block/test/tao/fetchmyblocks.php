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
    $block->dashboard->range('my,qa,project');
    $block->title->prefix('区块')->range('2-5');
    $block->order->range('5-2');

    $block->gen(4);
}

/**

title=blockTao->fetchMyBlocks();
timeout=0
cid=2

- 查询admin用户在我的地盘仪表盘的区块列表数量 @1
- 查询admin用户在我的地盘仪表盘的区块标题 @区块2
- 查询admin用户在我的地盘仪表盘的隐藏区块列表数量 @0
- 查询admin用户在不存在仪表盘的区块列表数量 @0
- 查询admin用户在我的地盘仪表盘区块的vision @rnd

*/
$tester->loadModel('block');

initData();

r(count($tester->block->fetchMyBlocks('my')))     && p('')         && e('1');       // 查询admin用户在我的地盘仪表盘的区块列表数量
r($tester->block->fetchMyBlocks('my'))            && p('2:title')  && e('区块2');   // 查询admin用户在我的地盘仪表盘的区块标题
r(count($tester->block->fetchMyBlocks('my', 1)))  && p('')         && e('0');       // 查询admin用户在我的地盘仪表盘的隐藏区块列表数量
r(count($tester->block->fetchMyBlocks('asdas?'))) && p('')         && e('0');       // 查询admin用户在不存在仪表盘的区块列表数量
r($tester->block->fetchMyBlocks('my'))            && p('2:vision') && e('rnd');     // 查询admin用户在我的地盘仪表盘区块的vision
