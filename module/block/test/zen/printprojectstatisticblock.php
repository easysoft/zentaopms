#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectStatisticBlock();
timeout=0
cid=0

- 步骤1:标准区块对象正常获取项目统计数据属性projectCount @10
- 步骤2:验证用户数量正确加载属性userCount @11
- 步骤3:使用type为wait过滤项目状态属性projectCount @3
- 步骤4:设置count为5限制项目数量属性projectCount @5
- 步骤5:使用type为doing过滤项目状态属性projectCount @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-20');
$project->name->range('项目{1-20}');
$project->status->range('wait{5},doing{5},suspended{5},closed{5}');
$project->type->range('project');
$project->model->range('scrum');
$project->PM->range('admin,user1,user2,user3,user4');
$project->deleted->range('0');
$project->gen(20);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0');
$user->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$blockTest = new blockZenTest();

// 5. 创建测试数据对象
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->type = 'all';
$block1->params->count = 15;

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->type = 'all';
$block2->params->count = 15;

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->type = 'wait';
$block3->params->count = 15;

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->type = 'all';
$block4->params->count = 5;

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->type = 'doing';
$block5->params->count = 15;

// 6. 必须包含至少5个测试步骤
r($blockTest->printProjectStatisticBlockTest($block1)) && p('projectCount') && e('10'); // 步骤1:标准区块对象正常获取项目统计数据
r($blockTest->printProjectStatisticBlockTest($block2)) && p('userCount') && e('11'); // 步骤2:验证用户数量正确加载
r($blockTest->printProjectStatisticBlockTest($block3)) && p('projectCount') && e('3'); // 步骤3:使用type为wait过滤项目状态
r($blockTest->printProjectStatisticBlockTest($block4)) && p('projectCount') && e('5'); // 步骤4:设置count为5限制项目数量
r($blockTest->printProjectStatisticBlockTest($block5)) && p('projectCount') && e('3'); // 步骤5:使用type为doing过滤项目状态