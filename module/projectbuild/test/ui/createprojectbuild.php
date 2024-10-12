#!/usr/bin/env php
<?php

/**

title=创建项目版本
timeout=0
cid=73

- 版本名称置空，检查提示信息
 - 测试结果 @创建项目版本表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建项目版本
 - 测试结果 @项目版本创建成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createprojectbuild.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->path->range('`,1,`');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2-3');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2');
$execution->path->range('`,1,2,`, `,1,3,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(2, false);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1,2,3');
$projectProduct->product->range('1');
$projectProduct->gen(3);

$tester = new createProjectBuildTester();
$tester->login();

//设置项目版本数据
$build = array(
    array('name' => ''),
    array('execution' => '项目1迭代2', 'name' => '版本1' . time()),
);

r($tester->checkNoNameInfo($build['0']))    && p('message,status') && e('创建项目版本表单页提示信息正确,SUCCESS'); // 版本名称置空，检查提示信息
r($tester->createProjectBuild($build['1'])) && p('message,status') && e('项目版本创建成功,SUCCESS');               // 创建项目版本

$tester->closeBrowser();
