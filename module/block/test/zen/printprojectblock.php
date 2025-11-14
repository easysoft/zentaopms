#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectBlock();
timeout=0
cid=15273

- 步骤1：正常情况
 - 属性projectCount @10
 - 属性userCount @6
- 步骤2：设置count为10
 - 属性projectCount @10
 - 属性userCount @6
- 步骤3：设置type为wait
 - 属性projectCount @5
 - 属性userCount @6
- 步骤4：设置orderBy为id_asc
 - 属性projectCount @10
 - 属性userCount @6
- 步骤5：count为0的边界情况
 - 属性projectCount @10
 - 属性userCount @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-20');
$project->name->range('项目{1-20}');
$project->status->range('wait{5},doing{5},suspended{5},closed{5}');
$project->type->range('project{10},sprint{5},kanban{5}');
$project->PM->range('admin,user1,user2,user3,user4');
$project->deleted->range('0{18},1{2}');
$project->gen(20);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 创建不同的block对象进行测试
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 15;
$block1->params->type = 'all';
$block1->params->orderBy = 'id_desc';

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->count = 10;
$block2->params->type = 'all';
$block2->params->orderBy = 'id_desc';

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->count = 15;
$block3->params->type = 'wait';
$block3->params->orderBy = 'id_desc';

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->count = 15;
$block4->params->type = 'all';
$block4->params->orderBy = 'id_asc';

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->count = 0;
$block5->params->type = 'all';
$block5->params->orderBy = 'id_desc';

r($blockTest->printProjectBlockTest($block1)) && p('projectCount,userCount') && e('10,6'); // 步骤1：正常情况
r($blockTest->printProjectBlockTest($block2)) && p('projectCount,userCount') && e('10,6'); // 步骤2：设置count为10
r($blockTest->printProjectBlockTest($block3)) && p('projectCount,userCount') && e('5,6'); // 步骤3：设置type为wait
r($blockTest->printProjectBlockTest($block4)) && p('projectCount,userCount') && e('10,6'); // 步骤4：设置orderBy为id_asc
r($blockTest->printProjectBlockTest($block5)) && p('projectCount,userCount') && e('10,6'); // 步骤5：count为0的边界情况