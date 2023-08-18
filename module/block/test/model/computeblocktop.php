#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-5');
    $block->account->range('admin');
    $block->dashboard->range('my');
    $block->module->range('welcome,guide,project,project');
    $block->code->range('welcome,guide,list,statistic');
    $block->title->prefix('区块名称')->range('1-4');
    $block->width->range('3,2,1,1');
    $block->height->range('3,3,6,6');
    $block->top->range('0,3,3,9');

    $block->gen(4);
}

/**

title=测试 blockModel->getByID();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('block');

initData();

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '2';
$block->vision    = 'rnd';

r($tester->block->computeBlockTop($block)) && p('') && e('6'); // 测试新加入的区块宽度为2的时候，top是否为6。

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '1';
$block->vision    = 'rnd';
r($tester->block->computeBlockTop($block)) && p('') && e('15'); // 测试新加入的区块宽度为1的时候，top是否为15。
