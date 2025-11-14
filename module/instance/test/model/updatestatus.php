#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::updateStatus();
timeout=0
cid=16824

- 执行instanceTest模块的updateStatusTest方法，参数是1, 'running'  @0
- 执行instanceTest模块的updateStatusTest方法，参数是2, 'stopped'  @0
- 执行instanceTest模块的updateStatusTest方法，参数是3, 'invalid_status'  @Array
- 执行instanceTest模块的updateStatusTest方法，参数是999, 'running'  @(
- 执行instanceTest模块的updateStatusTest方法，参数是4, ''  @[status] => Array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 准备测试数据
$instance = zenData('instance');
$instance->id->range('1-10');
$instance->name->range('app{1-10}');
$instance->status->range('running{3},stopped{3},creating{2},unknown{2}');
$instance->k8name->range('app{1-10}');
$instance->chart->range('zentao{5},jenkins{3},gitlab{2}');
$instance->space->range('1-3');
$instance->domain->range('app{1-10}');
$instance->deleted->range('0{10}');
$instance->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$instanceTest = new instanceTest();

// 测试步骤1：正常更新实例状态为running
r($instanceTest->updateStatusTest(1, 'running')) && p() && e(0);

// 测试步骤2：正常更新实例状态为stopped
r($instanceTest->updateStatusTest(2, 'stopped')) && p() && e(0);

// 测试步骤3：更新实例状态为无效状态值
r($instanceTest->updateStatusTest(3, 'invalid_status')) && p() && e('Array');

// 测试步骤4：更新不存在的实例状态
r($instanceTest->updateStatusTest(999, 'running')) && p() && e('(');

// 测试步骤5：使用空字符串状态进行更新
r($instanceTest->updateStatusTest(4, '')) && p() && e('[status] => Array');