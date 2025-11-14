#!/usr/bin/env php
<?php

/**

title=测试 executionTao::processStoryNode();
timeout=0
cid=16395

- 测试有需求和任务的节点处理属性type @module
- 测试只有任务无需求的节点处理属性type @module
- 测试空节点的处理属性type @module
- 测试需求有层级关系的节点处理属性type @module
- 测试节点ID为0的根节点处理属性type @module

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 项目执行数据
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->openedVersion->range('18.0');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

// 产品数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

// 模块数据
$module = zenData('module');
$module->id->range('1-10');
$module->name->range('1-10')->prefix('模块');
$module->root->range('1-3');
$module->parent->range('0,1{4},2{5}');
$module->type->range('task');
$module->gen(10);

// 需求数据
$story = zenData('story');
$story->id->range('1-15');
$story->product->range('1-3');
$story->module->range('1-10');
$story->parent->range('0{10},1{3},2{2}');
$story->title->range('1-15')->prefix('需求');
$story->type->range('story');
$story->status->range('active');
$story->stage->range('developing');
$story->pri->range('1-4');
$story->color->range('#3da7f5,#2dbea3,#fdc24a,#f2617a,#975fe4');
$story->assignedTo->range('admin,user1,user2,,closed');
$story->openedBy->range('admin,user1');
$story->gen(15);

// 项目需求关联数据
$projectstory = zenData('projectstory');
$projectstory->project->range('3-5');
$projectstory->product->range('1-3');
$projectstory->story->range('1-15');
$projectstory->version->range('1');
$projectstory->gen(15);

// 任务数据
$task = zenData('task');
$task->id->range('1-20');
$task->name->range('1-20')->prefix('任务');
$task->execution->range('3-5');
$task->module->range('1-10');
$task->story->range('0{5},1-15');
$task->type->range('test,devel,design');
$task->status->range('wait,doing,done');
$task->pri->range('1-4');
$task->estimate->range('1-10');
$task->left->range('0-10');
$task->consumed->range('1-5');
$task->assignedTo->range('admin,user1,user2');
$task->gen(20);

// 用户数据
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,closed');
$user->realname->range('管理员,用户1,用户2,用户3,已关闭');
$user->avatar->range('admin.jpg,user1.jpg,user2.jpg,user3.jpg,');
$user->gen(5);

// 分支数据
$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('1-3');
$branch->gen(5);

// 项目产品关联数据
$related = zenData('projectproduct');
$related->project->range('3-5');
$related->product->range('1-3');
$related->branch->range('0-1');
$related->gen(5);

zenData('team')->gen(0);
su('admin');

$executionTest = new executionTest();

// 创建测试节点对象
$nodeWithStories = new stdclass();
$nodeWithStories->id = 1;
$nodeWithStories->root = 1;
$nodeWithStories->name = '模块1';

$nodeWithTasks = new stdclass();
$nodeWithTasks->id = 2;
$nodeWithTasks->root = 1;
$nodeWithTasks->name = '模块2';

$emptyNode = new stdclass();
$emptyNode->id = 5;
$emptyNode->root = 2;
$emptyNode->name = '模块5';

$nodeWithHierarchy = new stdclass();
$nodeWithHierarchy->id = 1;
$nodeWithHierarchy->root = 1;
$nodeWithHierarchy->name = '模块1';

$rootNode = new stdclass();
$rootNode->id = 0;
$rootNode->root = 1;
$rootNode->name = '根节点';

r($executionTest->processStoryNodeWithDataTest($nodeWithStories, 3)) && p('type') && e('module'); // 测试有需求和任务的节点处理
r($executionTest->processStoryNodeWithDataTest($nodeWithTasks, 3)) && p('type') && e('module'); // 测试只有任务无需求的节点处理
r($executionTest->processStoryNodeWithDataTest($emptyNode, 3)) && p('type') && e('module'); // 测试空节点的处理
r($executionTest->processStoryNodeWithDataTest($nodeWithHierarchy, 3)) && p('type') && e('module'); // 测试需求有层级关系的节点处理
r($executionTest->processStoryNodeWithDataTest($rootNode, 3)) && p('type') && e('module'); // 测试节点ID为0的根节点处理