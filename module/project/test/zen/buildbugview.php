#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildBugView();
timeout=0
cid=0

- 步骤1：正常情况 @success
- 步骤2：无产品ID @success
- 步骤3：无项目ID @error
- 步骤4：空参数 @error
- 步骤5：大数据量分页 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->status->range('wait,doing,suspended,closed');
$project->hasProduct->range('1{8},0{2}');
$project->multiple->range('1{6},0{4}');
$project->gen(10);

$bug = zenData('bug');
$bug->id->range('1-20');
$bug->project->range('1-5');
$bug->product->range('1-3');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10,Bug11,Bug12,Bug13,Bug14,Bug15,Bug16,Bug17,Bug18,Bug19,Bug20');
$bug->status->range('active,resolved,closed');
$bug->openedBy->range('user1,user2,admin');
$bug->assignedTo->range('user1,user2,user3');
$bug->gen(20);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->buildBugViewTest(1, 1, (object)array('id' => 1, 'name' => '项目1', 'hasProduct' => 1, 'multiple' => 1), 'all', 0, 'id_desc', 0, 'all', array(1 => (object)array('id' => 1, 'name' => '产品1')), 20, 20, 1)) && p() && e('success'); // 步骤1：正常情况
r($projectTest->buildBugViewTest(0, 1, (object)array('id' => 1, 'name' => '项目1', 'hasProduct' => 1, 'multiple' => 1), 'all', 0, 'id_desc', 0, 'all', array(1 => (object)array('id' => 1, 'name' => '产品1')), 15, 20, 1)) && p() && e('success'); // 步骤2：无产品ID
r($projectTest->buildBugViewTest(1, 0, (object)array('id' => 0, 'name' => '', 'hasProduct' => 0, 'multiple' => 0), 'all', 0, 'id_desc', 0, 'all', array(), 10, 20, 1)) && p() && e('error'); // 步骤3：无项目ID
r($projectTest->buildBugViewTest('', '', null, '', '', '', '', '', array(), 0, 0, 0)) && p() && e('error'); // 步骤4：空参数
r($projectTest->buildBugViewTest(1, 1, (object)array('id' => 1, 'name' => '项目1', 'hasProduct' => 1, 'multiple' => 1), 'all', 0, 'id_desc', 0, 'all', array(1 => (object)array('id' => 1, 'name' => '产品1')), 1000, 100, 10)) && p() && e('success'); // 步骤5：大数据量分页