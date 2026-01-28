#!/usr/bin/env php
<?php

/**

title=测试 biModel::getMultiData();
timeout=0
cid=15175

- 步骤1：正常情况测试单个y轴指标 @status
- 步骤2：多个y轴指标测试
 - 第1条的0属性 @id
 - 第1条的1属性 @name
- 步骤3：包含过滤条件 @status
- 步骤4：启用排序功能 @status
- 步骤5：空设置参数边界测试 @~~
- 步骤6：不同聚合函数测试 @status
- 步骤7：日期分组测试 @openedDate

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-20');
$task->name->range('任务{1-20}');
$task->status->range('wait{8},doing{7},done{5}');
$task->assignedTo->range('admin{10},user1{5},user2{5}');
$task->openedDate->range('`2024-01-15 10:00:00`,`2024-02-15 10:00:00`,`2024-03-15 10:00:00`');
$task->deleted->range('0');
$task->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->getMultiDataTest(array('xaxis' => array(array('field' => 'status')), 'yaxis' => array(array('field' => 'id', 'valOrAgg' => 'count'))), 'SELECT id, status FROM zt_task WHERE deleted = "0"', array(), 'mysql', false)) && p('0') && e('status'); // 步骤1：正常情况测试单个y轴指标
r($biTest->getMultiDataTest(array('xaxis' => array(array('field' => 'status')), 'yaxis' => array(array('field' => 'id', 'valOrAgg' => 'count'), array('field' => 'name', 'valOrAgg' => 'count'))), 'SELECT id, name, status FROM zt_task WHERE deleted = "0"', array(), 'mysql', false)) && p('1:0,1') && e('id,name'); // 步骤2：多个y轴指标测试
r($biTest->getMultiDataTest(array('xaxis' => array(array('field' => 'status')), 'yaxis' => array(array('field' => 'id', 'valOrAgg' => 'count'))), 'SELECT id, status FROM zt_task WHERE deleted = "0"', array('status' => array('operator' => '=', 'value' => "'wait'")), 'mysql', false)) && p('0') && e('status'); // 步骤3：包含过滤条件
r($biTest->getMultiDataTest(array('xaxis' => array(array('field' => 'status')), 'yaxis' => array(array('field' => 'id', 'valOrAgg' => 'count'))), 'SELECT id, status FROM zt_task WHERE deleted = "0"', array(), 'mysql', true)) && p('0') && e('status'); // 步骤4：启用排序功能
r($biTest->getMultiDataTest(array('xaxis' => array(), 'yaxis' => array()), 'SELECT id FROM zt_task WHERE deleted = "0"', array(), 'mysql', false)) && p('0') && e('~~'); // 步骤5：空设置参数边界测试
r($biTest->getMultiDataTest(array('xaxis' => array(array('field' => 'status')), 'yaxis' => array(array('field' => 'id', 'valOrAgg' => 'sum'))), 'SELECT id, status FROM zt_task WHERE deleted = "0"', array(), 'mysql', false)) && p('0') && e('status'); // 步骤6：不同聚合函数测试
r($biTest->getMultiDataTest(array('xaxis' => array(array('field' => 'openedDate', 'group' => 'YEAR')), 'yaxis' => array(array('field' => 'id', 'valOrAgg' => 'count'))), 'SELECT id, openedDate FROM zt_task WHERE deleted = "0"', array(), 'mysql', false)) && p('0') && e('openedDate'); // 步骤7：日期分组测试