#!/usr/bin/env php
<?php

/**

title=项目列表页tab标签检查
timeout=0
cid=73

- 检查all标签数量
 - 测试结果 @all标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查undone标签数量
 - 测试结果 @undone标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查wait标签数量
 - 测试结果 @wait标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查doing标签数量
 - 测试结果 @doing标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查suspended标签数量
 - 测试结果 @suspended标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查closed标签数量
 - 测试结果 @closed标签下条数显示正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/browsetab.ui.class.php';

$project = zenData('project');
$project->id->range('1-8');
$project->project->range('0');
$project->model->range('scrum{3}, waterfall{3}, kanban{2}');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');
$project->grade->range('1');
$project->name->range('敏捷项目1, 敏捷项目2, 敏捷项目3, 瀑布项目4, 瀑布项目5, 瀑布项目6, 看板项目7, 看板项目8');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('undone{3}, wait{2}, doing, suspended, closed');
$project->gen(8);

$tester = new browseTabTester();
$tester->login();

r($tester->checkBrowseTab('all', '8'))       && p('message,status') && e('all标签下条数显示正确,SUCCESS');        // 检查all标签数量
r($tester->checkBrowseTab('undone', '3'))    && p('message,status') && e('undone标签下条数显示正确,SUCCESS');     // 检查undone标签数量
r($tester->checkBrowseTab('wait', '2'))      && p('message,status') && e('wait标签下条数显示正确,SUCCESS');       // 检查wait标签数量
r($tester->checkBrowseTab('doing', '1'))     && p('message,status') && e('doing标签下条数显示正确,SUCCESS');      // 检查doing标签数量
r($tester->checkBrowseTab('suspended', '1')) && p('message,status') && e('suspended标签下条数显示正确,SUCCESS');  // 检查suspended标签数量
r($tester->checkBrowseTab('closed', '1'))    && p('message,status') && e('closed标签下条数显示正确,SUCCESS');     // 检查closed标签数量

$tester->closeBrowser();
