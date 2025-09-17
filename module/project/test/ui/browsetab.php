#!/usr/bin/env php
<?php

/**

title=项目列表页tab标签检查
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
include '../lib/ui/browsetab.ui.class.php';

$project = zenData('project');
$project->id->range('1-9');
$project->project->range('0');
$project->model->range('scrum{3}, waterfall{4}, kanban{2}');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');
$project->grade->range('1');
$project->name->range('敏捷项目1, 敏捷项目2, 敏捷项目3, 瀑布项目4, 瀑布项目5, 瀑布项目6, 瀑布项目7, 看板项目8, 看板项目9');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait{3}, doing{2}, suspended{2}, closed{2}');
$project->gen(9);

$tester = new browseTabTester();
$tester->login();

r($tester->checkBrowseTab('all', '9'))       && p('message,status') && e('全部标签下条数显示正确,SUCCESS');    // 检查全部标签数量
r($tester->checkBrowseTab('undone', '5'))    && p('message,status') && e('未完成标签下条数显示正确,SUCCESS');  // 检查未完成标签数量
r($tester->checkBrowseTab('wait', '3'))      && p('message,status') && e('未开始标签下条数显示正确,SUCCESS');  // 检查未开始标签数量
r($tester->checkBrowseTab('doing', '2'))     && p('message,status') && e('进行中标签下条数显示正确,SUCCESS');  // 检查进行中标签数量
r($tester->checkBrowseTab('suspended', '2')) && p('message,status') && e('已挂起标签下条数显示正确,SUCCESS');  // 检查已挂起标签数量
r($tester->checkBrowseTab('delayed', '0'))   && p('message,status') && e('已延期标签下条数显示正确,SUCCESS');  // 检查已延期标签数量
r($tester->checkBrowseTab('closed', '2'))    && p('message,status') && e('已关闭标签下条数显示正确,SUCCESS');  // 检查已关闭标签数量

$tester->closeBrowser();
