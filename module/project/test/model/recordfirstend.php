#!/usr/bin/env php
<?php

/**

title=测试 projectModel::recordFirstEnd();
timeout=0
cid=17866

- 步骤1：正常项目，有有效end日期 @1
- 步骤2：正常项目，end为零日期 @1
- 步骤3：正常项目，有有效end日期 @1
- 步骤4：正常项目，有有效end日期 @1
- 步骤5：正常项目，end为空 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-5');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->type->range('project{5}');
$table->status->range('doing{5}');
$table->begin->range('20240101 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$table->end->range('20241231 000000:0,00000000 000000:0,20250131 000000:0,20240630 000000:0,[]')->type('timestamp')->format('YYYY-MM-DD');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->recordFirstEndTest(1)) && p() && e('1'); // 步骤1：正常项目，有有效end日期
r($projectTest->recordFirstEndTest(2)) && p() && e('1'); // 步骤2：正常项目，end为零日期
r($projectTest->recordFirstEndTest(3)) && p() && e('1'); // 步骤3：正常项目，有有效end日期
r($projectTest->recordFirstEndTest(4)) && p() && e('1'); // 步骤4：正常项目，有有效end日期
r($projectTest->recordFirstEndTest(5)) && p() && e('1'); // 步骤5：正常项目，end为空