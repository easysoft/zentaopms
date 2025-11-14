#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignReportData();
timeout=0
cid=0

- 步骤1:create方法模式下的正常数据分配属性begin @2024-01-01
- 步骤2:view方法模式下的数据分配属性end @2024-02-28
- 步骤3:空报告数据数组处理属性begin @2024-01-01
- 步骤4:包含单个productIdList的数据转换属性productIdList @1
- 步骤5:view模式数据分配验证属性begin @2024-04-01

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
// assignReportData 方法主要进行数据转换和视图分配,不需要复杂的数据库准备

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$testreportTest = new testreportTest();

// 5. 🔴 强制要求:必须包含至少5个测试步骤
r($testreportTest->assignReportDataTest(array('begin' => '2024-01-01', 'end' => '2024-01-31', 'productIdList' => array(1 => 1), 'tasks' => array(1 => 'task1'), 'stories' => array(), 'bugs' => array(), 'execution' => (object)array('id' => 1, 'name' => 'Test'), 'builds' => array(), 'owner' => 'admin', 'cases' => ''), 'create', null)) && p('begin') && e('2024-01-01'); // 步骤1:create方法模式下的正常数据分配
r($testreportTest->assignReportDataTest(array('begin' => '2024-02-01', 'end' => '2024-02-28', 'productIdList' => array(2 => 2), 'tasks' => array(2 => 'task2'), 'stories' => array(), 'bugs' => array(), 'execution' => (object)array('id' => 2, 'name' => 'Test2'), 'builds' => array(), 'owner' => 'user1', 'cases' => ''), 'view', null)) && p('end') && e('2024-02-28'); // 步骤2:view方法模式下的数据分配
r($testreportTest->assignReportDataTest(array(), 'create', null)) && p('begin') && e('2024-01-01'); // 步骤3:空报告数据数组处理
r($testreportTest->assignReportDataTest(array('begin' => '2024-03-01', 'end' => '2024-03-31', 'productIdList' => array(1 => 1), 'tasks' => array(1 => 'task1'), 'stories' => array(), 'bugs' => array(), 'execution' => (object)array('id' => 3, 'name' => 'Test3'), 'builds' => array(), 'owner' => 'admin', 'cases' => ''), 'create', null)) && p('productIdList') && e('1'); // 步骤4:包含单个productIdList的数据转换
r($testreportTest->assignReportDataTest(array('begin' => '2024-04-01', 'end' => '2024-04-30', 'productIdList' => array(1 => 1), 'tasks' => array(1 => 'task1'), 'stories' => array(), 'bugs' => array(), 'execution' => (object)array('id' => 5, 'name' => 'Test5'), 'builds' => array(), 'owner' => 'tester', 'cases' => ''), 'view', null)) && p('begin') && e('2024-04-01'); // 步骤5:view模式数据分配验证