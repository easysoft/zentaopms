#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildBugView();
timeout=0
cid=17925

- 步骤1:正常项目bug视图构建
 - 属性productID @1
 - 属性projectID @1
- 步骤2:指定产品ID的bug视图属性productID @2
- 步骤3:指定类型的bug视图
 - 属性type @all
 - 属性orderBy @id_desc
- 步骤4:指定版本的bug视图属性buildID @1
- 步骤5:验证项目ID正确属性projectID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 禁用废弃警告
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

// 2. zendata数据准备（根据需要配置）
zendata('bug')->loadYaml('buildbugview', false, 2)->gen(20);
zendata('project')->loadYaml('buildbugview', false, 2)->gen(5);
zendata('product')->loadYaml('buildbugview', false, 2)->gen(5);
zendata('user')->loadYaml('buildbugview', false, 2)->gen(10);
zendata('build')->loadYaml('buildbugview', false, 2)->gen(5);

$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->product->range('1-2');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5');
$storyTable->type->range('story');
$storyTable->status->range('active');
$storyTable->gen(5);

$taskTable = zenData('task');
$taskTable->id->range('1-5');
$taskTable->project->range('1-2');
$taskTable->execution->range('1-2');
$taskTable->name->range('任务1,任务2,任务3,任务4,任务5');
$taskTable->type->range('devel');
$taskTable->status->range('doing');
$taskTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('101-105');
$executionTable->project->range('1{3},2{2}');
$executionTable->name->range('迭代1,迭代2,迭代3,迭代4,迭代5');
$executionTable->type->range('sprint');
$executionTable->status->range('doing');
$executionTable->gen(5);

$productPlanTable = zenData('productplan');
$productPlanTable->id->range('1-3');
$productPlanTable->product->range('1-2');
$productPlanTable->branch->range('0');
$productPlanTable->title->range('计划1,计划2,计划3');
$productPlanTable->status->range('doing');
$productPlanTable->gen(3);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1,1,2,2,3');
$projectProductTable->product->range('1,2,1,2,3');
$projectProductTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 获取项目对象和产品信息
$project1 = $projectTest->objectModel->getByID(1);
$project2 = $projectTest->objectModel->getByID(2);
$project3 = $projectTest->objectModel->getByID(3);

// 如果项目对象获取失败，创建默认对象
if(empty($project1))
{
    $project1 = new stdClass();
    $project1->id = 1;
    $project1->name = '项目1';
    $project1->hasProduct = 1;
    $project1->model = 'scrum';
    $project1->multiple = 1;
}
if(empty($project2))
{
    $project2 = new stdClass();
    $project2->id = 2;
    $project2->name = '项目2';
    $project2->hasProduct = 1;
    $project2->model = 'waterfall';
    $project2->multiple = 1;
}
if(empty($project3))
{
    $project3 = new stdClass();
    $project3->id = 3;
    $project3->name = '项目3';
    $project3->hasProduct = 0;
    $project3->model = 'kanban';
    $project3->multiple = 0;
}

$products = $projectTest->objectModel->loadModel('product')->getPairs();
if(empty($products)) $products = array(1 => '产品1', 2 => '产品2');

// 5. 强制要求：必须包含至少5个测试步骤
r($projectTest->buildBugViewTest(1, 1, $project1, 'all', 0, 'id_desc', 0, '', $products, 20, 20, 1)) && p('productID,projectID') && e('1,1'); // 步骤1:正常项目bug视图构建
r($projectTest->buildBugViewTest(2, 1, $project1, 'all', 0, 'id_desc', 0, '', $products, 20, 20, 1)) && p('productID') && e('2'); // 步骤2:指定产品ID的bug视图
r($projectTest->buildBugViewTest(0, 1, $project1, 'all', 0, 'id_desc', 0, '', $products, 20, 20, 1)) && p('type,orderBy') && e('all,id_desc'); // 步骤3:指定类型的bug视图
r($projectTest->buildBugViewTest(1, 1, $project1, 'all', 0, 'id_desc', 1, '', $products, 20, 20, 1)) && p('buildID') && e('1'); // 步骤4:指定版本的bug视图
r($projectTest->buildBugViewTest(1, 2, $project2, 'all', 0, 'id_desc', 0, '', $products, 20, 20, 1)) && p('projectID') && e('2'); // 步骤5:验证项目ID正确