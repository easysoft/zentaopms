#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

$userModel = new userModel();
$user = $userModel->identify('admin', 'Asd123456');
$userModel->login($user);

function initData()
{
    $block = zdTable('block');
    $block->id->range('2-5');
    $block->account->range('admin,test1,test2');
    $block->vision->range('rnd,lite');
    $block->module->range('my,qa,project');
    $block->title->prefix('区块')->range('2-5');
    $block->dashboard->range('my,qa');
    $block->order->range('5-2');

    $block->gen(4);
}

/**

title=测试 blockModel->getMaxOrderByDashboard();
timeout=0
cid=1

- 执行block模块的getByID方法，参数是2
 - 属性account @admin
 - 属性vision @rnd
 - 属性module @my
 - 属性title @区块2
 - 属性source @my
 - 属性block @bug
 - 属性order @5


*/

global $tester;
$tester->loadModel('block');

initData();

r($tester->block->getMaxOrderByDashboard('my')) && p('') && e('5'); //  测试 my 模块下最大order 为 5
r($tester->block->getMaxOrderByDashboard('my')) && p('') && e('5'); //  测试 my 模块下最大order 为 5
r($tester->block->getMaxOrderByDashboard('bug')) && p('') && e('0'); // 测试数据库中不存在 模块的最大排序  为 0
