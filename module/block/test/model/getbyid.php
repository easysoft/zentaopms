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

title=测试 blockModel->getByID();
timeout=0
cid=1

- 测试获取正常的block的内容
 - 属性account @admin
 - 属性vision @rnd
 - 属性dashboard @my
 - 属性module @project
 - 属性code @list
 - 属性title @区块名称2
 - 属性order @1

- 测试获取正常的block的内容属性order @3

- 测试获取不存在的block的内容 @0

*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getByID(2)) && p('account,vision,dashboard,module,code,title,order') && e('admin,rnd,my,project,list,区块名称2,1');        // 测试获取正常的block的内容
r($tester->block->getByID(4)) && p('order') && e('3');        // 测试获取正常的block的内容
r($tester->block->getByID(6)) && p('') && e('0');        // 测试获取不存在的block的内容