#!/usr/bin/env php
<?php

/**

title=项目下解决Bug操作检查
timeout=0
cid=1

- 执行tester模块的resolveBug方法，参数是$bug[0]
 - 最终测试状态 @SUCCESS
 - 测试结果 @解决Bug表单页提示信息正确
- 执行tester模块的resolveBug方法，参数是$bug[1]
 - 最终测试状态 @SUCCESS
 - 测试结果 @解决Bug成功

*/

chdir(__DIR__);
include '../lib/bug.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->project->range('1');
$bug->product->range('1{5}, 2{5}');
$bug->execution->range('0');
$bug->title->range('Bug1, Bug2, Bug3, Bug4, Bug5, Bug6, Bug7, Bug8, Bug9, Bug10');
$bug->status->range('active{2}, resolved{2}, closed{1}, active{2}, resolved{2}, closed{1}');
$bug->assignedTo->range('[]');
$bug->gen(10);

$team = zendata('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->join->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$team->gen(1);

$tester = new bugTester();
$tester->login();

$bug = array(
    array('resolution' => '', 'build' => ''),
    array('resolution' => '已解决', 'build' => '主干'),
);

r($tester->resolveBug($bug[0])) && p('status,message') && e('SUCCESS,解决Bug表单页提示信息正确');
r($tester->resolveBug($bug[1])) && p('status,message') && e('SUCCESS,解决Bug成功');

$tester->closeBrowser();
