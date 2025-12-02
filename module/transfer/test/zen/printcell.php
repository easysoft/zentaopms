#!/usr/bin/env php
<?php

/**

title=测试 transferZen::printCell();
timeout=0
cid=0

- 步骤1：测试select控件生成 @1
- 步骤2：测试hidden控件生成 @1
- 步骤3：测试date控件生成 @1
- 步骤4：测试textarea控件生成 @1
- 步骤5：测试普通input控件生成 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(strpos($transferTest->printCellTest('task', 'status', 'select', 'status[1]', 'wait', array('wait' => '未开始', 'doing' => '进行中', 'done' => '已完成'), 1), '<select') !== false) && p() && e('1'); // 步骤1：测试select控件生成
r(strpos($transferTest->printCellTest('task', 'execution', 'hidden', 'execution[1]', '101', array(), 1), "type='hidden'") !== false) && p() && e('1'); // 步骤2：测试hidden控件生成
r(strpos($transferTest->printCellTest('task', 'deadline', 'date', 'deadline[1]', '2025-12-31', array(), 1), 'form-date') !== false) && p() && e('1'); // 步骤3：测试date控件生成
r(strpos($transferTest->printCellTest('task', 'desc', 'textarea', 'desc[1]', '任务描述', array(), 1), '<textarea') !== false) && p() && e('1'); // 步骤4：测试textarea控件生成
r(strpos($transferTest->printCellTest('task', 'name', 'input', 'name[1]', '任务名称', array(), 1), 'form-control') !== false) && p() && e('1'); // 步骤5：测试普通input控件生成