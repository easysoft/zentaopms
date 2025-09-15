#!/usr/bin/env php
<?php

/**

title=测试 productZen::processProjectListData();
timeout=0
cid=0

- 步骤1：正常项目列表数据处理第0条的name属性 @测试项目1
- 步骤2：空项目列表数据处理 @0
- 步骤3：单个项目数据处理验证第0条的name属性 @单个测试项目
- 步骤4：多个项目数据批量处理 @2
- 步骤5：项目数据字段完整性验证第0条的from属性 @project

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

su('admin');

$productTest = new productTest();

// 准备简单的测试数据
$projectList = array();
$project1 = new stdClass();
$project1->id = 1;
$project1->name = '测试项目1';
$project1->PM = 'admin';
$project1->status = 'doing';
$project1->budget = '100000';
$project1->budgetUnit = 'CNY';
$project1->estimate = 0;
$project1->consumed = 0;
$project1->left = 0;
$project1->hasProduct = 1;
$projectList[] = $project1;

$project2 = new stdClass();
$project2->id = 2;
$project2->name = '测试项目2';
$project2->PM = 'user1';
$project2->status = 'wait';
$project2->budget = '200000';
$project2->budgetUnit = 'CNY';
$project2->estimate = 0;
$project2->consumed = 0;
$project2->left = 0;
$project2->hasProduct = 1;
$projectList[] = $project2;

$emptyProjectList = array();

$singleProject = array();
$project = new stdClass();
$project->id = 1;
$project->name = '单个测试项目';
$project->PM = 'admin';
$project->status = 'doing';
$project->budget = '150000';
$project->budgetUnit = 'CNY';
$project->estimate = 0;
$project->consumed = 0;
$project->left = 0;
$project->hasProduct = 1;
$singleProject[] = $project;

r($productTest->processProjectListDataTest($projectList)) && p('0:name') && e('测试项目1'); // 步骤1：正常项目列表数据处理
r($productTest->processProjectListDataTest($emptyProjectList)) && p() && e('0'); // 步骤2：空项目列表数据处理
r($productTest->processProjectListDataTest($singleProject)) && p('0:name') && e('单个测试项目'); // 步骤3：单个项目数据处理验证
r($productTest->processProjectListDataTest($projectList)) && p() && e('2'); // 步骤4：多个项目数据批量处理
r($productTest->processProjectListDataTest($projectList)) && p('0:from') && e('project'); // 步骤5：项目数据字段完整性验证