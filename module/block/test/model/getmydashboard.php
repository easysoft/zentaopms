#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-3');
    $block->account->range('admin');
    $block->vision->range('rnd');
    $block->module->range('test,my');
    $block->title->prefix('区块')->range('3,2');
    $block->hidden->range('0-1');
    $block->order->range('3-2');
    $block->dashboard->range('test,my');

    $block->gen(2);
}

/**

title=测试 block 模块 model下的 getMyDashboard 方法
timeout=0
cid=39
*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getMyDashboard('test')) && p('2:account,title') && e('admin,区块3');
r($tester->block->getMyDashboard('my', 1)) && p('3:account,title') && e('admin,区块2');
