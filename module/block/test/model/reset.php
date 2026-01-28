#!/usr/bin/env php
<?php

/**

title=测试 blockModel::reset();
timeout=0
cid=15234

- 执行blockTest模块的resetTest方法，参数是'my'  @1
- 执行blockTest模块的resetTest方法，参数是''  @1
- 执行blockTest模块的resetTest方法，参数是'nonexistent'  @1
- 执行blockTest模块的resetTest方法，参数是'test-dashboard_123'  @1
- 执行blockTest模块的resetTest方法，参数是'very-long-dashboard-name-for-testing-purposes'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$blockTable = zenData('block');
$blockTable->id->range('1-5');
$blockTable->account->range('admin{3}, user{2}');
$blockTable->dashboard->range('my{2}, project{1}, qa{1}, product{1}');
$blockTable->module->range('product{2}, project{2}, qa{1}');
$blockTable->title->range('我的产品, 我的项目, 测试统计, 任务列表, 概览');
$blockTable->code->range('overview{2}, statistic{2}, list{1}');
$blockTable->width->range('1{3}, 2{2}');
$blockTable->height->range('3{4}, 4{1}');
$blockTable->hidden->range('0');
$blockTable->vision->range('rnd');
$blockTable->gen(5);

$configTable = zenData('config');
$configTable->id->range('1-3');
$configTable->owner->range('admin{2}, user{1}');
$configTable->module->range('my{1}, project{1}, qa{1}');
$configTable->section->range('common');
$configTable->key->range('blockInited');
$configTable->value->range('1');
$configTable->vision->range('rnd');
$configTable->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$blockTest = new blockModelTest();

// 测试步骤1：测试有效仪表盘名称的重置功能
r($blockTest->resetTest('my')) && p() && e('1');

// 测试步骤2：测试空字符串仪表盘名称
r($blockTest->resetTest('')) && p() && e('1');

// 测试步骤3：测试不存在的仪表盘名称
r($blockTest->resetTest('nonexistent')) && p() && e('1');

// 测试步骤4：测试包含特殊字符的仪表盘名称
r($blockTest->resetTest('test-dashboard_123')) && p() && e('1');

// 测试步骤5：测试长仪表盘名称
r($blockTest->resetTest('very-long-dashboard-name-for-testing-purposes')) && p() && e('1');