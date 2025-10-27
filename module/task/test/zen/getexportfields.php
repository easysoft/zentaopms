#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getExportFields();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性id @编号
 - 属性name @任务名称
 - 属性status @任务状态
- 步骤2：POST参数
 - 属性assignedTo @指派给
 - 属性openedBy @由谁创建
- 步骤3：新UI组件兼容
 - 属性pri @优先级
 - 属性estimate @最初预计
 - 属性consumed @总计消耗
- 步骤4：不存在语言标签
 - 属性id @编号
 - 属性name @任务名称
 - 属性nonexist @nonexist
- 步骤5：空字段输入 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// getExportFields方法不直接依赖数据库，主要测试逻辑处理

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->getExportFieldsTest('id,name,status', array())) && p('id,name,status') && e('编号,任务名称,任务状态'); // 步骤1：正常情况
r($taskTest->getExportFieldsTest('id,name', array('exportFields' => array('assignedTo', 'openedBy')))) && p('assignedTo,openedBy') && e('指派给,由谁创建'); // 步骤2：POST参数
r($taskTest->getExportFieldsTest('id,name', array('exportFields' => array('pri,estimate,consumed')))) && p('pri,estimate,consumed') && e('优先级,最初预计,总计消耗'); // 步骤3：新UI组件兼容
r($taskTest->getExportFieldsTest('id,name,nonexist', array())) && p('id,name,nonexist') && e('编号,任务名称,nonexist'); // 步骤4：不存在语言标签
r($taskTest->getExportFieldsTest('', array())) && p() && e('0'); // 步骤5：空字段输入