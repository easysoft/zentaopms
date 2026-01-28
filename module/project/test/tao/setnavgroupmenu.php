#!/usr/bin/env php
<?php

/**

title=测试 projectTao::setNavGroupMenu();
timeout=0
cid=17918

- 步骤1：正常情况 @1
- 步骤2：空导航组 @1
- 步骤3：不存在的导航组 @1
- 步骤4：executionID为0 @1
- 步骤5：复杂菜单结构 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->status->range('wait,doing{2},closed{2}');
$project->multiple->range('1{3},0{2}');
$project->model->range('scrum{2},waterfall{2},kanban{1}');
$project->PM->range('admin,user1,user2,admin,user1');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->setNavGroupMenuTest('project', 1, (object)array('id' => 1, 'name' => '项目1'))) && p() && e('1'); // 步骤1：正常情况
r($projectTest->setNavGroupMenuTest('', 1, (object)array('id' => 1, 'name' => '项目1'))) && p() && e('1'); // 步骤2：空导航组
r($projectTest->setNavGroupMenuTest('nonexistent', 1, (object)array('id' => 1, 'name' => '项目1'))) && p() && e('1'); // 步骤3：不存在的导航组
r($projectTest->setNavGroupMenuTest('project', 0, (object)array('id' => 1, 'name' => '项目1'))) && p() && e('1'); // 步骤4：executionID为0
r($projectTest->setNavGroupMenuTest('waterfall', 2, (object)array('id' => 2, 'name' => '项目2', 'model' => 'waterfall'))) && p() && e('1'); // 步骤5：复杂菜单结构