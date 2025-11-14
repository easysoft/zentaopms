#!/usr/bin/env php
<?php

/**

title=测试 blockModel::getMyDashboard();
timeout=0
cid=15232

- 步骤1：正常情况，查询admin用户my仪表盘的区块数量 @5
- 步骤2：验证排序，检查第一个区块的宽度第0条的width属性 @3
- 步骤3：边界值测试，查询不存在的仪表盘 @0
- 步骤4：空参数测试 @0
- 步骤5：验证返回区块的账户属性第0条的account属性 @admin
- 步骤6：验证返回的区块都不是隐藏状态第0条的hidden属性 @0
- 步骤7：验证返回的区块都是当前vision第0条的vision属性 @rnd

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 准备测试数据
$block = zenData('block');
$block->id->range('1-10');
$block->account->range('admin{5},user{3},test{2}');
$block->dashboard->range('my{5},product{3},project{2}');
$block->module->range('welcome,guide,project,task,bug,story,report,metric,chart,test');
$block->code->range('welcome,guide,list,statistic,list,list,list,list,list,test');
$block->title->prefix('区块名称')->range('1-10');
$block->width->range('3,2,2,1,1,1,2,1,2,1');
$block->top->range('0,1,0,1,2,0,1,2,0,1');
$block->hidden->range('0{8},1{2}');
$block->vision->range('rnd{10}');
$block->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$blockTest = new blockTest();

r(count($blockTest->getMyDashboardTest('my'))) && p('') && e('5'); // 步骤1：正常情况，查询admin用户my仪表盘的区块数量
r($blockTest->getMyDashboardTest('my')) && p('0:width') && e('3'); // 步骤2：验证排序，检查第一个区块的宽度
r(count($blockTest->getMyDashboardTest('nonexistent'))) && p('') && e('0'); // 步骤3：边界值测试，查询不存在的仪表盘
r(count($blockTest->getMyDashboardTest(''))) && p('') && e('0'); // 步骤4：空参数测试
r($blockTest->getMyDashboardTest('my')) && p('0:account') && e('admin'); // 步骤5：验证返回区块的账户属性
r($blockTest->getMyDashboardTest('my')) && p('0:hidden') && e('0'); // 步骤6：验证返回的区块都不是隐藏状态
r($blockTest->getMyDashboardTest('my')) && p('0:vision') && e('rnd'); // 步骤7：验证返回的区块都是当前vision