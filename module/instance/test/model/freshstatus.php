#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::freshStatus();
timeout=0
cid=16794

- 步骤1：正常状态刷新，设置runDuration为0属性runDuration @0
- 步骤2：CNE查询失败，runDuration保持为0属性runDuration @0
- 步骤3：状态变化验证属性status @creating
- 步骤4：版本信息验证属性version @1.0.0
- 步骤5：实例ID验证属性id @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('instance');
$table->id->range('1-5');
$table->name->range('Test Instance{1-5}');
$table->status->range('running,stopped,creating,running,stopped');
$table->version->range('1.0.0,1.1.0,2.0.0,1.0.0,2.0.0');
$table->k8name->range('test-k8name{1-5}');
$table->chart->range('zentao,gitlab,jenkins,zentao,gitlab');
$table->domain->range('test1.example.com,test2.example.com,test3.example.com,test4.example.com,test5.example.com');
$table->space->range('1-3:R');
$table->deleted->range('0');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 创建测试实例对象，包含必要的属性
$instance1 = (object)array('id' => 1, 'status' => 'running', 'version' => '1.0.0', 'k8name' => 'test-k8name1', 'chart' => 'zentao', 'spaceData' => (object)array('k8space' => 'test-space'));
$instance2 = (object)array('id' => 2, 'status' => 'stopped', 'version' => '1.1.0', 'k8name' => 'invalid-k8name', 'chart' => 'gitlab', 'spaceData' => (object)array('k8space' => 'test-space'));
$instance3 = (object)array('id' => 3, 'status' => 'creating', 'version' => '2.0.0', 'k8name' => 'test-k8name3', 'chart' => 'jenkins', 'spaceData' => (object)array('k8space' => 'test-space'));
$instance4 = (object)array('id' => 4, 'status' => 'running', 'version' => '1.0.0', 'k8name' => 'test-k8name4', 'chart' => 'zentao', 'spaceData' => (object)array('k8space' => 'test-space'));
$instance5 = (object)array('id' => 5, 'status' => 'stopped', 'version' => '2.0.0', 'k8name' => 'test-k8name5', 'chart' => 'gitlab', 'spaceData' => (object)array('k8space' => 'test-space'));

r($instanceTest->freshStatusTest($instance1)) && p('runDuration') && e('0'); // 步骤1：正常状态刷新，设置runDuration为0
r($instanceTest->freshStatusTest($instance2)) && p('runDuration') && e('0'); // 步骤2：CNE查询失败，runDuration保持为0
r($instanceTest->freshStatusTest($instance3)) && p('status') && e('creating'); // 步骤3：状态变化验证
r($instanceTest->freshStatusTest($instance4)) && p('version') && e('1.0.0'); // 步骤4：版本信息验证
r($instanceTest->freshStatusTest($instance5)) && p('id') && e('5'); // 步骤5：实例ID验证