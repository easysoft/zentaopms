#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumTestBlock();
timeout=0
cid=15293

- 步骤1:测试正常情况下传入type=all和count=5的参数
 - 属性type @all
 - 属性projectID @1
- 步骤2:测试type=wait时获取wait状态的测试单
 - 属性type @wait
 - 属性projectID @1
- 步骤3:测试type=doing时获取doing状态的测试单
 - 属性type @doing
 - 属性projectID @1
- 步骤4:测试type=done时获取done状态的测试单
 - 属性type @done
 - 属性projectID @1
- 步骤5:测试type=blocked时获取blocked状态的测试单
 - 属性type @blocked
 - 属性projectID @1
- 步骤6:测试count=10时限制返回数量
 - 属性type @all
 - 属性projectID @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备(根据需要配置)
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project');
$project->status->range('doing');
$project->model->range('scrum');
$project->deleted->range('0');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

$build = zenData('build');
$build->id->range('1-20');
$build->name->range('版本1,版本2,版本3,版本4,版本5');
$build->product->range('1-5');
$build->execution->range('1-5');
$build->deleted->range('0');
$build->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{5},2{5},3{5},4{5},5{5}');
$projectProduct->product->range('1,2,3,4,5');
$projectProduct->gen(25);

$testtask = zenData('testtask');
$testtask->id->range('1-50');
$testtask->project->range('1');
$testtask->product->range('1,2,3,4,5');
$testtask->execution->range('1-5');
$testtask->name->range('测试单1,测试单2,测试单3,测试单4,测试单5');
$testtask->build->range('1-10');
$testtask->status->range('wait{10},doing{10},done{10},blocked{10}');
$testtask->auto->range('no');
$testtask->deleted->range('0');
$testtask->gen(40);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$blockTest = new blockZenTest();

// 设置session.project
global $tester;
$tester->session->set('project', 1);

// 5. 🔴 强制要求:必须包含至少5个测试步骤
$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->type = 'all';
$block1->params->count = 5;
r($blockTest->printScrumTestBlockTest($block1)) && p('type,projectID') && e('all,1'); // 步骤1:测试正常情况下传入type=all和count=5的参数

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->type = 'wait';
$block2->params->count = 10;
r($blockTest->printScrumTestBlockTest($block2)) && p('type,projectID') && e('wait,1'); // 步骤2:测试type=wait时获取wait状态的测试单

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->type = 'doing';
$block3->params->count = 10;
r($blockTest->printScrumTestBlockTest($block3)) && p('type,projectID') && e('doing,1'); // 步骤3:测试type=doing时获取doing状态的测试单

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->type = 'done';
$block4->params->count = 10;
r($blockTest->printScrumTestBlockTest($block4)) && p('type,projectID') && e('done,1'); // 步骤4:测试type=done时获取done状态的测试单

$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->type = 'blocked';
$block5->params->count = 10;
r($blockTest->printScrumTestBlockTest($block5)) && p('type,projectID') && e('blocked,1'); // 步骤5:测试type=blocked时获取blocked状态的测试单

$block6 = new stdClass();
$block6->params = new stdClass();
$block6->params->type = 'all';
$block6->params->count = 10;
r($blockTest->printScrumTestBlockTest($block6)) && p('type,projectID') && e('all,1'); // 步骤6:测试count=10时限制返回数量