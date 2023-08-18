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
    $block->width->range('2,2,1,1');

    $block->gen(4);
}

/**

title=测试 blockModel->getBlockInitStatus();
timeout=0
cid=1

- 测试 my 仪表盘下返回为 1 @1

- 测试 product 仪表盘下返回为 0 @0

- 测试不存在的仪表盘返回为 0 @0

- 测试不传仪表盘参数返回为 0 @0

*/

global $tester;
$tester->loadModel('block');

initData();
$tester->loadModel('setting')->setItem("admin.my.common.blockInited@rnd", '1');
$tester->loadModel('setting')->setItem("admin.my.block.initVersion", 'rnd');

r($tester->block->getBlockInitStatus('my'))      && p('') && e('1');   //  测试 my 仪表盘下返回为 1
r($tester->block->getBlockInitStatus('product')) && p('') && e('0');   //  测试 product 仪表盘下返回为 0
r($tester->block->getBlockInitStatus('asda??'))  && p('') && e('0');   //  测试不存在的仪表盘返回为 0
r($tester->block->getBlockInitStatus(''))        && p('') && e('0');   //  测试不传仪表盘参数返回为 0
