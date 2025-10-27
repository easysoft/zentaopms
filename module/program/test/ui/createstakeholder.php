#!/usr/bin/env php
<?php

/**

title=项目集下创建干系人测试
timeout=0
cid=1

- 校验用户不能为空
 - 测试结果 @创建干系人表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 创建项目团队成员干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS
- 创建公司干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS
- 创建关键干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS
- 创建外部干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS

*/

chdir(__DIR__);
include '../lib/ui/createstakeholder.ui.class.php';
global $config;

$stakeholder = zenData('stakeholder');
$stakeholder->gen(0);

$user = zenData('user');
$user->id->range('1-5');
$user->type->range('inside{4}, outside{1}');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, user1, user2, user3, user4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->visions->range('rnd');
$user->gen(5);

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0');
$project->type->range('program, project');
$project->model->range('[], scrum');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1, 2');
$project->name->range('项目集A, 敏捷项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-96w)-(-95w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+96w)-(+97w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(2);

$team = zendata('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin, user1, user2');
$team->join->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$team->gen(1);

$tester = new createstakeholderTester();
$tester->login();

$stakeholder = array(
    array('user' => ''),
    array('user' => 'user1'),
    array('user' => 'user2', 'type' => 'company'),
    array('user' => 'user3', 'key' => 'key'),
    array('user' => 'user4', 'type' => 'outside'),
);

r($tester->createStakeholder($stakeholder['0'])) && p('message,status') && e('创建干系人表单页提示信息正确, SUCCESS'); //校验用户不能为空
r($tester->createStakeholder($stakeholder['1'])) && p('message,status') && e('创建干系人成功, SUCCESS'); //创建项目团队成员干系人
r($tester->createStakeholder($stakeholder['2'])) && p('message,status') && e('创建干系人成功, SUCCESS'); //创建公司干系人
r($tester->createStakeholder($stakeholder['3'])) && p('message,status') && e('创建干系人成功, SUCCESS'); //创建关键干系人
r($tester->createStakeholder($stakeholder['4'])) && p('message,status') && e('创建干系人成功, SUCCESS'); //创建外部干系人

$tester->closeBrowser();
