#!/usr/bin/env php
<?php

/**

title=测试 designZen::setMenu();
timeout=0
cid=0

- 步骤1：正常waterfall项目
 - 属性waterfall_menu_exists @1
 - 属性waterfall_submenu_exists @1
 - 属性submenu_all_exists @1
- 步骤2：正常waterfallplus项目
 - 属性waterfall_menu_exists @1
 - 属性waterfall_submenu_exists @1
 - 属性submenu_count @5
- 步骤3：非瀑布项目属性waterfall_menu_exists @~~
- 步骤4：空项目ID属性waterfall_menu_exists @~~
- 步骤5：IPD版本项目
 - 属性waterfall_menu_exists @1
 - 属性ipd_menu_copied @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/design.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->model->range('waterfall{3},waterfallplus{2},scrum{3},ipd{2}');
$project->status->range('wait{2},doing{5},suspended{1},closed{2}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$designTest = new designTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($designTest->setMenuTest(1, 1, 'all')) && p('waterfall_menu_exists,waterfall_submenu_exists,submenu_all_exists') && e('1,1,1'); // 步骤1：正常waterfall项目
r($designTest->setMenuTest(2, 2, 'hlds')) && p('waterfall_menu_exists,waterfall_submenu_exists,submenu_count') && e('1,1,5'); // 步骤2：正常waterfallplus项目  
r($designTest->setMenuTest(6, 0, '')) && p('waterfall_menu_exists') && e('~~'); // 步骤3：非瀑布项目
r($designTest->setMenuTest(0, 1, 'all')) && p('waterfall_menu_exists') && e('~~'); // 步骤4：空项目ID
r($designTest->setMenuTest(9, 3, 'dds')) && p('waterfall_menu_exists,ipd_menu_copied') && e('1,~~'); // 步骤5：IPD版本项目