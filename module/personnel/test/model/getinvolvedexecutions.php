#!/usr/bin/env php
<?php

/**

title=测试 personnelModel::getInvolvedExecutions();
timeout=0
cid=17329

- 步骤1：正常项目包含执行的情况-验证admin用户执行数量属性admin @5
- 步骤2：检查用户1的执行统计数量属性user1 @5
- 步骤3：空项目数组输入 @0
- 步骤4：不存在的项目ID @0
- 步骤5：混合有效和无效项目ID-只统计有效项目属性admin @3
- 步骤6：项目下无执行的情况 @0
- 步骤7：多个项目包含不同类型执行-用户数量 @3

*/

declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 数据准备 - 项目数据
$projectTable = zenData('project');
$projectTable->id->range('11-15,101-105');
$projectTable->type->range('project{5},stage{3},sprint{2}');
$projectTable->project->range('11{3},12{2}');  // 执行属于项目11或12
$projectTable->deleted->range('0');
$projectTable->gen(10);

// 数据准备 - 团队数据
$teamTable = zenData('team');
$teamTable->id->range('1-15');
$teamTable->root->range('101{3},102{3},103{3},104{3},105{3}');  // 执行ID
$teamTable->type->range('execution');
$teamTable->account->range('admin,user1,user2');
$teamTable->gen(15);

su('admin');

$personnel = new personnelModelTest();

// 测试数据定义
$validProjects = array(11, 12);
$emptyProjects = array();
$invalidProjects = array(9999);
$mixedProjects = array(11, 9999);
$noExecutionProjects = array(13);
$multipleProjects = array(11, 12, 16);

// 执行测试步骤
r($personnel->getInvolvedExecutionsTest($validProjects)) && p('admin') && e('5'); // 步骤1：正常项目包含执行的情况-验证admin用户执行数量
r($personnel->getInvolvedExecutionsTest($validProjects)) && p('user1') && e('5'); // 步骤2：检查用户1的执行统计数量
r($personnel->getInvolvedExecutionsTest($emptyProjects)) && p() && e('0'); // 步骤3：空项目数组输入
r($personnel->getInvolvedExecutionsTest($invalidProjects)) && p() && e('0'); // 步骤4：不存在的项目ID
r($personnel->getInvolvedExecutionsTest($mixedProjects)) && p('admin') && e('3'); // 步骤5：混合有效和无效项目ID-只统计有效项目
r($personnel->getInvolvedExecutionsTest($noExecutionProjects)) && p() && e('0'); // 步骤6：项目下无执行的情况
r(count($personnel->getInvolvedExecutionsTest($multipleProjects))) && p() && e('3'); // 步骤7：多个项目包含不同类型执行-用户数量