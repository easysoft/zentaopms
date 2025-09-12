#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectStatisticBlock();
timeout=0
cid=0

- 执行blockTest模块的printProjectStatisticBlockTest方法，参数是$normalBlock  @1
- 执行blockTest模块的printProjectStatisticBlockTest方法，参数是$emptyBlock  @1
- 执行blockTest模块的printProjectStatisticBlockTest方法，参数是$invalidBlock  @1
- 执行blockTest模块的printProjectStatisticBlockTest方法，参数是$largeBlock  @1
- 执行blockTest模块的printProjectStatisticBlockTest方法，参数是$waitBlock  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zenData('project');
$project = zenData('project');
$project->id->range('1-50');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->status->range('wait{10},doing{15},done{20},suspended{5}');
$project->model->range('scrum{20},waterfall{15},kanban{10},agileplus{5}');
$project->type->range('project{40},program{10}');
$project->deleted->range('0{45},1{5}');
$project->gen(50);

zenData('user');
$user = zenData('user');
$user->account->range('admin,user1,user2,user3,user4,user5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5');
$user->deleted->range('0');
$user->gen(6);

su('admin');

$blockTest = new blockTest();

// 创建测试块参数对象
$normalBlock = new stdclass();
$normalBlock->params = new stdclass();
$normalBlock->params->type = 'all';
$normalBlock->params->count = 15;

$emptyBlock = new stdclass();
$emptyBlock->params = new stdclass();
$emptyBlock->params->type = '';
$emptyBlock->params->count = 0;

$invalidBlock = new stdclass();
$invalidBlock->params = new stdclass();
$invalidBlock->params->type = 'test@#$';
$invalidBlock->params->count = 10;

$largeBlock = new stdclass();
$largeBlock->params = new stdclass();
$largeBlock->params->type = 'all';
$largeBlock->params->count = 100;

$waitBlock = new stdclass();
$waitBlock->params = new stdclass();
$waitBlock->params->type = 'wait';
$waitBlock->params->count = 15;

r($blockTest->printProjectStatisticBlockTest($normalBlock)) && p() && e('1');
r($blockTest->printProjectStatisticBlockTest($emptyBlock)) && p() && e('1');
r($blockTest->printProjectStatisticBlockTest($invalidBlock)) && p() && e('1');
r($blockTest->printProjectStatisticBlockTest($largeBlock)) && p() && e('1');
r($blockTest->printProjectStatisticBlockTest($waitBlock)) && p() && e('1');