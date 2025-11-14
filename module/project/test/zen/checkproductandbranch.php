#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkProductAndBranch();
timeout=0
cid=17935

- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata 属性products[0] @最少关联一个产品
- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata 属性branch[0][] @分支不能为空！
- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkProductAndBranchTest方法，参数是$project, $rawdata  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->type->range('normal{5},branch{3},platform{2}');
$product->program->range('1-5');
$product->deleted->range('0{9},1{1}');
$product->gen(10);

$program = zenData('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$program->type->range('program');
$program->parent->range('0');
$program->grade->range('1');
$program->gen(5);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-10');
$projectproduct->product->range('1-10');
$projectproduct->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 强制要求:必须包含至少5个测试步骤

// 步骤1:正常情况 - 有关联产品且不为空
$project = new stdClass();
$project->hasProduct = 1;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->products = array(1, 2);
$rawdata->branch = array(array(0), array(0));
$rawdata->addProduct = '';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p() && e('1');

// 步骤2:项目有产品但未关联产品且不添加产品 - 应该返回错误
$project = new stdClass();
$project->hasProduct = 1;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->products = array();
$rawdata->branch = array();
$rawdata->addProduct = '';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p('products[0]') && e('最少关联一个产品');

// 步骤3:多分支产品分支为空 - 应该返回分支为空错误
$project = new stdClass();
$project->hasProduct = 1;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->products = array(6);
$rawdata->branch = array(array(''));
$rawdata->addProduct = '';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p('branch[0][]') && e('分支不能为空！');

// 步骤4:关联产品数量为0但设置addProduct - 应该返回true
$project = new stdClass();
$project->hasProduct = 1;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->products = array();
$rawdata->branch = array();
$rawdata->addProduct = 'on';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p() && e('1');

// 步骤5:多分支产品有多个分支且有效 - 应该返回true
$project = new stdClass();
$project->hasProduct = 1;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->products = array(6, 7);
$rawdata->branch = array(array(1, 2), array(1));
$rawdata->addProduct = '';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p() && e('1');

// 步骤6:边界值测试 - parent为空时返回true
$project = new stdClass();
$project->hasProduct = 1;
$project->parent = 0;
$rawdata = new stdClass();
$rawdata->products = array();
$rawdata->branch = array();
$rawdata->addProduct = '';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p() && e('1');

// 步骤7:无product情况 - hasProduct为0时返回true
$project = new stdClass();
$project->hasProduct = 0;
$project->parent = 0;
$rawdata = new stdClass();
$rawdata->products = array();
$rawdata->branch = array();
$rawdata->addProduct = '';
r($projectzenTest->checkProductAndBranchTest($project, $rawdata)) && p() && e('1');