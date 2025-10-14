#!/usr/bin/env php
<?php

/**

title=运营界面项目列表页标签检查
timeout=0
cid=73

- 检查全部标签数量
 - 测试结果 @全部标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查未完成标签数量
 - 测试结果 @未完成标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查未开始标签数量
 - 测试结果 @未开始标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查进行中标签数量
 - 测试结果 @进行中标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查已挂起标签数量
 - 测试结果 @已挂起标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查已延期标签数量
 - 测试结果 @已延期标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查已关闭标签数量
 - 测试结果 @已关闭标签下条数显示正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/browsetablite.ui.class.php';

$project = zenData('project');
$project->id->range('1-9');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('[]');
$project->grade->range('1');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`');
$project->name->range('运营界面项目1, 运营界面项目2, 运营界面项目3, 运营界面项目4, 运营界面项目5, 运营界面项目6, 运营界面项目7, 运营界面项目8, 运营界面项目9');
$project->hasProduct->range('0');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait{3}, doing{2}, suspended{2}, closed{2}');
$project->vision->range('lite');
$project->gen(9);

$product = zenData('product');
$product->id->range('1-9');
$product->name->range('影子产品1, 影子产品2, 影子产品3, 影子产品4, 影子产品5, 影子产品6, 影子产品7, 影子产品8, 影子产品9');
$product->shadow->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(9);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-9');
$projectProduct->product->range('1-9');
$projectProduct->gen(9);

$tester = new browseTabLiteTester();
$tester->login();

r($tester->checkBrowseTab('all', '9'))       && p('message,status') && e('全部标签下条数显示正确,SUCCESS');   // 检查全部标签数量
r($tester->checkBrowseTab('undone', '5'))    && p('message,status') && e('未完成标签下条数显示正确,SUCCESS'); // 检查未完成标签数量
r($tester->checkBrowseTab('wait', '3'))      && p('message,status') && e('未开始标签下条数显示正确,SUCCESS'); // 检查未开始标签数量
r($tester->checkBrowseTab('doing', '2'))     && p('message,status') && e('进行中标签下条数显示正确,SUCCESS'); // 检查进行中标签数量
r($tester->checkBrowseTab('suspended', '2')) && p('message,status') && e('已挂起标签下条数显示正确,SUCCESS'); // 检查已挂起标签数量
r($tester->checkBrowseTab('delayed', '0'))   && p('message,status') && e('已延期标签下条数显示正确,SUCCESS'); // 检查已延期标签数量
r($tester->checkBrowseTab('closed', '2'))    && p('message,status') && e('已关闭标签下条数显示正确,SUCCESS'); // 检查已关闭标签数量

$tester->closeBrowser();
