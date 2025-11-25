#!/usr/bin/env php
<?php

/**

title=测试 executionZen::correctExecutionCommonLang();
timeout=0
cid=16424

- 步骤1：空项目对象 @0
- 步骤2：kanban模式项目 @1
- 步骤3：waterfall模式项目 @1
- 步骤4：waterfallplus模式项目 @1
- 步骤5：无产品项目 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-5');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->model->range('kanban{1},waterfall{1},waterfallplus{1},scrum{1},agileplus{1}');
$table->hasProduct->range('1{3},0{2}');
$table->type->range('project{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->correctExecutionCommonLangTest(null, 'normal')) && p() && e('0'); // 步骤1：空项目对象
r($executionTest->correctExecutionCommonLangTest(1, 'normal')) && p() && e('1'); // 步骤2：kanban模式项目
r($executionTest->correctExecutionCommonLangTest(2, 'normal')) && p() && e('1'); // 步骤3：waterfall模式项目
r($executionTest->correctExecutionCommonLangTest(3, 'normal')) && p() && e('1'); // 步骤4：waterfallplus模式项目
r($executionTest->correctExecutionCommonLangTest(4, 'normal')) && p() && e('1'); // 步骤5：无产品项目