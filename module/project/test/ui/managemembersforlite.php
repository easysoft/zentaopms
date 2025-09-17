#!/usr/bin/env php
<?php

/**

title=运营界面项目团队管理
timeout=0
cid=1

- 添加项目团队成员
 - 测试结果 @项目团队成员添加成功
 - 最终测试状态 @SUCCESS
- 删除项目已有的团队成员
 - 测试结果 @项目团队成员删除成功
 - 最终测试状态 @SUCCESS
- 复制部门成员
 - 测试结果 @复制部门团队成员成功
 - 最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/ui/managemembersforlite.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('1{3}, 2{1}');
$user->account->range('admin, user1, user2, user3');
$user->realname->range('admin, 用户1, 用户2, 用户3');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(4);

$team = zenData('team');
$team->gen(0);

$team = zenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->days->range('7');
$team->gen(1);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->hasProduct->range('0');
$project->name->range('运营项目1');
$project->path->range('`,1,`');
$project->vision->range('lite');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$dept = zenData('dept');
$dept->id->range('1-3');
$dept->name->range('部门1, 部门2, 部门1-1');
$dept->parent->range('0, 0, 1');
$dept->path->range('`,1,`, `,2,`, `,1,3,`');
$dept->grade->range('1, 1, 2');
$dept->gen(3);

$tester = new manageMembersForLiteTester();
$tester->login();

//设置敏捷项目执行数据
$members = array(
    array('account' => '用户1', 'role' => '开发人员'),
);

r($tester->addMembers($members['0'])) && p('message,status') && e('项目团队成员添加成功,SUCCESS');  //添加项目团队成员
r($tester->deleteMembers())           && p('message,status') && e('项目团队成员删除成功,SUCCESS');  //删除项目已有的团队成员
r($tester->copyDeptMembers())         && p('message,status') && e('复制部门团队成员成功,SUCCESS');  //复制部门成员

$tester->closeBrowser();
