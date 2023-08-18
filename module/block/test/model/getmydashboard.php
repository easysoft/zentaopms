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

title=测试 block 模块 model下的 getMyDashboard 方法
timeout=0
cid=39

- 查询admin用户在我的地盘仪表盘的区块列表数量 @4

- 查询admin用户在我的地盘仪表盘的区块标题第2条的title属性第2条的title属性 @区块名称3

- 查询admin用户在不存在仪表盘的区块列表数量 @0

*/

global $tester;
$tester->loadModel('block');

initData();

r(count($tester->block->getMyDashboard('my')))      && p('')         && e('4');         // 查询admin用户在我的地盘仪表盘的区块列表数量
r($tester->block->getMyDashboard('my'))             && p('2:title')  && e('区块名称3'); // 查询admin用户在我的地盘仪表盘的区块标题第2条的title属性
r(count($tester->block->getMyDashboard('product'))) && p('')         && e('0');         // 查询admin用户在不存在仪表盘的区块列表数量
