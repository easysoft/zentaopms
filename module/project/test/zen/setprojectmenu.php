#!/usr/bin/env php
<?php

/**

title=测试 projectZen::setProjectMenu();
timeout=0
cid=17967

- 步骤1：program tab设置菜单属性program_tab @program menu set
- 步骤2：project tab设置菜单属性project_tab @project menu set
- 步骤3：其他tab值无操作属性other_tab @no menu action
- 步骤4：空tab值无操作属性empty_tab @no menu action
- 步骤5：参数验证测试 @projectID parameter cannot be null

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->parent->range('1-5');
$table->type->range('project');
$table->status->range('wait,doing,closed');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->setProjectMenuTest(1, 1)) && p('program_tab') && e('program menu set'); // 步骤1：program tab设置菜单
r($projectTest->setProjectMenuTest(2, 1)) && p('project_tab') && e('project menu set'); // 步骤2：project tab设置菜单
r($projectTest->setProjectMenuTest(3, 2)) && p('other_tab') && e('no menu action'); // 步骤3：其他tab值无操作
r($projectTest->setProjectMenuTest(4, 2)) && p('empty_tab') && e('no menu action'); // 步骤4：空tab值无操作
r($projectTest->setProjectMenuTest(null, 1)) && p() && e('projectID parameter cannot be null'); // 步骤5：参数验证测试